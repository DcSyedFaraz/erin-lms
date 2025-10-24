<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionEvent extends Model
{
    protected $fillable = [
        'user_id',
        'local_subscription_id',
        'stripe_subscription_id',
        'type',
        'status',
        'stripe_price_id',
        'quantity',
        'trial_ends_at',
        'ends_at',
        'occurred_at',
        'payload',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
        'occurred_at' => 'datetime',
        'payload' => 'array',
    ];
}

