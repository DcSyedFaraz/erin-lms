<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
    ];

    protected $casts = [
        'is_featured' => 'bool',
        'is_published' => 'bool',
        'published_at' => 'datetime',
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

    public function isVideoLike(): bool
    {
        return in_array($this->content_type, ['curated_video', 'short_film', 'short_clip'], true);
    }

    protected function syncTierPriority(): void
    {
        $this->access_priority = SubscriptionPlan::tierPriority($this->access_tier);
    }
}
