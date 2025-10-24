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

    public function features()
    {
        return $this->hasMany(SubscriptionFeature::class, 'subscription_plan_id')->orderBy('sort_order');
    }
}
