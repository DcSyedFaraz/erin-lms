<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();
        $subscription = $user?->subscription('default');
        $plan = null;
        if ($subscription && $subscription->stripe_price) {
            $plan = SubscriptionPlan::where('stripe_price_id', $subscription->stripe_price)->first();
        }
        return view('subscriptions.me', compact('subscription', 'plan'));
    }

    public function checkout(Request $request, SubscriptionPlan $plan): RedirectResponse
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $secret = config('services.stripe.secret');
        if (!$secret) {
            return back()->with('error', 'Stripe is not configured.');
        }

        $stripe = new \Stripe\StripeClient($secret);

        // Create a subscription Checkout Session
        $sessionData = [
            'mode' => 'subscription',
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $plan->stripe_price_id,
                    'quantity' => 1,
                ]
            ],
            'success_url' => route('subscriptions.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('subscriptions.cancel'),
            'metadata' => [
                'user_id' => (string) $user->id,
                'plan_id' => (string) $plan->id,
            ],
        ];

        if (!empty($user->stripe_id)) {
            $sessionData['customer'] = $user->stripe_id;
        }

        $session = $stripe->checkout->sessions->create($sessionData);

        return redirect($session->url);
    }

    public function success(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('home')->with('error', 'Missing checkout session.');
        }

        $secret = config('services.stripe.secret');
        $stripe = new \Stripe\StripeClient($secret);

        $session = $stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['subscription', 'customer'],
        ]);

        $customerId = is_string($session->customer)
            ? $session->customer
            : ($session->customer->id ?? null);

        // Ensure we have a stripe customer on the user record
        if ($customerId && (!$user->stripe_id || $user->stripe_id !== $customerId)) {
            $user->stripe_id = $customerId;
            $user->save();
        }
        $subId = is_string($session->subscription)
            ? $session->subscription
            : ($session->subscription->id ?? null);

        if (!$subId) {
            return redirect()->route('home')->with('error', 'Subscription not completed.');
        }

        $subscription = $stripe->subscriptions->retrieve($subId, [
            'expand' => ['items.data.price.product']
        ]);

        // Find existing local subscription for this user and update instead of creating a new one
        $existing = DB::table('subscriptions')
            ->where('user_id', $user->id)
            ->where('type', 'default')
            ->orderByDesc('id')
            ->first();

        $localId = null;
        if ($existing) {
            if ($existing->stripe_id && $existing->stripe_id !== $subscription->id) {
                try {
                    $stripe->subscriptions->cancel($existing->stripe_id);
                } catch (\Throwable $e) {
                    // swallow
                }
            }

            DB::table('subscriptions')->where('id', $existing->id)->update([
                'stripe_id' => $subscription->id,
                'stripe_status' => $subscription->status,
                'stripe_price' => optional($subscription->items->data[0] ?? null)->price->id,
                'quantity' => optional($subscription->items->data[0] ?? null)->quantity ?? 1,
                'trial_ends_at' => $subscription->trial_end ? date('Y-m-d H:i:s', $subscription->trial_end) : null,
                'ends_at' => null,
                'updated_at' => now(),
            ]);
            $localId = $existing->id;
        } else {
            $localId = DB::table('subscriptions')->insertGetId([
                'user_id' => $user->id,
                'type' => 'default',
                'stripe_id' => $subscription->id,
                'stripe_status' => $subscription->status,
                'stripe_price' => optional($subscription->items->data[0] ?? null)->price->id,
                'quantity' => optional($subscription->items->data[0] ?? null)->quantity ?? 1,
                'trial_ends_at' => $subscription->trial_end ? date('Y-m-d H:i:s', $subscription->trial_end) : null,
                'ends_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Replace items for the local subscription
        DB::table('subscription_items')->where('subscription_id', $localId)->delete();
        $item = $subscription->items->data[0] ?? null;
        if ($item) {
            $productRef = $item->price->product ?? null;
            $productId = is_string($productRef) ? $productRef : ($productRef->id ?? null);
            DB::table('subscription_items')->insert([
                'subscription_id' => $localId,
                'stripe_id' => $item->id,
                'stripe_product' => $productId,
                'stripe_price' => $item->price->id,
                'quantity' => $item->quantity ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Log application-side update event
        try {
            \App\Models\SubscriptionEvent::create([
                'user_id' => $user->id,
                'local_subscription_id' => $localId,
                'stripe_subscription_id' => $subscription->id,
                'type' => 'app.subscription.updated',
                'status' => $subscription->status,
                'stripe_price_id' => $item?->price?->id,
                'quantity' => $item?->quantity ?? 1,
                'trial_ends_at' => $subscription->trial_end ? date('Y-m-d H:i:s', $subscription->trial_end) : null,
                'ends_at' => null,
                'occurred_at' => now(),
                'payload' => null,
            ]);
        } catch (\Throwable $e) {}

        return redirect()->route('home')->with('success', 'Subscription activated successfully.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('membership')->with('info', 'Subscription checkout cancelled.');
    }
}
