<?php

namespace App\Http\Middleware;

use App\Models\SubscriptionPlan;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  string|null  $minimumTier
     */
    public function handle(Request $request, Closure $next, ?string $minimumTier = null): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $currentTier = $user->subscriptionTierKey();
        if (!$currentTier) {
            return redirect()->route('membership')->with('error', 'An active subscription is required to access the video library.');
        }

        if ($minimumTier) {
            $currentPriority = SubscriptionPlan::tierPriority($currentTier);
            $requiredPriority = SubscriptionPlan::tierPriority($minimumTier);
            if ($currentPriority < $requiredPriority) {
                return redirect()->route('membership')
                    ->with('error', 'Upgrade your plan to view this section of the video library.');
            }
        }

        return $next($request);
    }
}
