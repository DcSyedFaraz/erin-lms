<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'video_library_item_id',
        'access_type',
        'amount',
        'currency',
        'status',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'purchased_at',
        'rental_expires_at',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'rental_expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(VideoLibraryItem::class, 'video_library_item_id');
    }

    public function isRental(): bool
    {
        return $this->access_type === 'rent';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isActive(): bool
    {
        if (! $this->isPaid()) {
            return false;
        }

        if (! $this->isRental()) {
            return true;
        }

        return $this->rental_expires_at === null || $this->rental_expires_at->isFuture();
    }
}
