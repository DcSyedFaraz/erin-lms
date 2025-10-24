<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $subscription = $user?->subscription('default');

        $plan = null;
        if ($subscription && $subscription->stripe_price) {
            $plan = SubscriptionPlan::where('stripe_price_id', $subscription->stripe_price)->first();
        }

        // Local history from subscription events (latest 5)
        $history = DB::table('subscription_events')
            ->where('user_id', $user->id)
            ->orderByDesc('occurred_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('parent.subscriptions.index', compact('subscription', 'plan', 'history'));
    }
}
