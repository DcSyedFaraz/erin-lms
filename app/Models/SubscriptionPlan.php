<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    public const TIER_LEVELS = [
        'silver' => 1,
        'golden' => 2,
        'platinum' => 3,
    ];

    public const TIER_COPY = [
        'silver' => [
            'tagline' => 'The Silver Plan offers users a foundational experience with a curated collection of videos, poems, and short clips.',
            'access' => 'Basic curated content library',
            'updates' => 'Every 2 weeks',
            'purpose' => 'Ideal for casual users who want to explore creative and inspiring content at an affordable rate.',
        ],
        'golden' => [
            'tagline' => 'The Golden Plan builds upon the Silver tier by unlocking additional premium experiences, such as short films, artistic performances, and exclusive series.',
            'access' => 'Includes all Silver content plus premium additions',
            'updates' => 'Weekly',
            'purpose' => 'Suited for users who seek a richer, more diverse entertainment experience with frequent updates.',
        ],
        'platinum' => [
            'tagline' => 'The Platinum Plan delivers the complete experience, combining all Silver and Golden content while adding early access to releases, behind-the-scenes interviews, and other special materials.',
            'access' => 'Full content library (Silver + Golden) plus exclusive extras',
            'updates' => 'Weekly, with early-access privileges',
            'purpose' => 'Tailored for dedicated users who want first access to everything and enjoy exclusive insights and bonus content.',
        ],
    ];

    protected $table = 'subscription_plans';

    protected $fillable = [
        'name',
        'stripe_price_id',
        'price',
        'interval',
        'is_trial',
        'tier_key',
        'tier_priority',
        'access_summary',
        'content_updates_summary',
        'purpose_summary',
    ];

    protected $casts = [
        'is_trial' => 'bool',
    ];

    public function features()
    {
        return $this->hasMany(SubscriptionFeature::class, 'subscription_plan_id')->orderBy('sort_order');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('tier_priority')->orderBy('price');
    }

    public static function tierPriority(?string $tierKey): int
    {
        return self::TIER_LEVELS[$tierKey] ?? 0;
    }

    public static function tierMeta(?string $tierKey): array
    {
        return self::TIER_COPY[$tierKey] ?? [
            'tagline' => null,
            'access' => null,
            'updates' => null,
            'purpose' => null,
        ];
    }

    public function getTierDisplayLabelAttribute(): ?string
    {
        if (!$this->tier_key) {
            return null;
        }

        return ucfirst($this->tier_key) . ' Plan';
    }
}
