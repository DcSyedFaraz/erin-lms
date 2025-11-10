<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function childProfiles()
    {
        return $this->hasMany(ChildProfile::class, 'user_id');
    }

    public function currentSubscriptionPlan(): ?SubscriptionPlan
    {
        $subscription = $this->subscription('default');
        if (!$subscription || !$subscription->stripe_price) {
            return null;
        }

        return SubscriptionPlan::where('stripe_price_id', $subscription->stripe_price)->first();
    }

    public function subscriptionTierKey(): ?string
    {
        return $this->currentSubscriptionPlan()?->tier_key;
    }

    public function subscriptionTierPriority(): int
    {
        return SubscriptionPlan::tierPriority($this->subscriptionTierKey());
    }

    public function canAccessTier(?string $tierKey): bool
    {
        if (!$tierKey) {
            return false;
        }

        if (!$this->subscription('default')) {
            return false;
        }

        return $this->subscriptionTierPriority() >= SubscriptionPlan::tierPriority($tierKey);
    }
}
