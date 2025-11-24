<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoLibraryItem extends Model
{
    use HasFactory;

    public const CONTENT_TYPES = [
        'curated_video' => 'Curated Video',
        'poem' => 'Poem',
        'short_film' => 'Premium Short Film',
        'short_clip' => 'Short Clip',
    ];

    protected $fillable = [
        'title',
        'slug',
        'content_type',
        'description',
        'body',
        'media_path',
        'thumbnail_path',
        'external_url',
        'access_tier',
        'access_priority',
        'is_featured',
        'is_published',
        'published_at',
        'uploaded_by',
        'is_standalone',
        'standalone_price',
        'standalone_rental_price',
        'standalone_rental_hours',
        'standalone_category',
    ];

    protected $casts = [
        'is_featured' => 'bool',
        'is_published' => 'bool',
        'is_standalone' => 'bool',
        'published_at' => 'datetime',
        'standalone_price' => 'float',
        'standalone_rental_price' => 'float',
        'standalone_rental_hours' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $item) {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->title) . '-' . Str::random(6);
            }
            $item->syncTierPriority();
        });

        static::updating(function (self $item) {
            $item->syncTierPriority();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $builder) {
                $builder->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeForTier(Builder $query, ?string $tierKey): Builder
    {
        $priority = SubscriptionPlan::tierPriority($tierKey);
        if ($priority === 0) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('access_priority', '<=', $priority);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(VideoPurchase::class, 'video_library_item_id');
    }

    public function activePurchaseFor(User $user): ?VideoPurchase
    {
        return $this->purchases()
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->where(function (Builder $query) {
                $query->whereNull('rental_expires_at')
                    ->orWhere('rental_expires_at', '>', now());
            })
            ->latest('purchased_at')
            ->first();
    }

    public function getMediaUrlAttribute(): ?string
    {
        if ($this->media_path) {
            return Storage::disk('public')->url($this->media_path);
        }

        return $this->external_url;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_path) {
            return Storage::disk('public')->url($this->thumbnail_path);
        }

        return null;
    }

    public function isPoem(): bool
    {
        return $this->content_type === 'poem';
    }

    public function isStandaloneEnabled(): bool
    {
        return $this->is_standalone && ($this->standalone_price !== null || $this->standalone_rental_price !== null);
    }

    public function rentalDurationHours(): int
    {
        return (int) ($this->standalone_rental_hours ?: 72);
    }

    public function supportsRental(): bool
    {
        return $this->standalone_rental_price !== null;
    }

    public function buyPrice(): ?float
    {
        return $this->standalone_price !== null ? (float) $this->standalone_price : null;
    }

    public function rentPrice(): ?float
    {
        return $this->standalone_rental_price !== null ? (float) $this->standalone_rental_price : null;
    }

    public function isVideoLike(): bool
    {
        return in_array($this->content_type, ['curated_video', 'short_film', 'short_clip'], true);
    }

    protected function syncTierPriority(): void
    {
        $this->access_priority = SubscriptionPlan::tierPriority($this->access_tier);
    }
}
