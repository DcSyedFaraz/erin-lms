<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plans';
    protected $fillable = [
        'name',
        'stripe_price_id',
        'price',
        'interval',
        'is_trial',
    ];
}
