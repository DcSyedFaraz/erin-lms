<?php

namespace App\Http\Controllers;

use App\Models\VideoLibraryItem;
use App\Models\VideoPurchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoPurchaseController extends Controller
{
    public function checkout(Request $request, VideoLibraryItem $videoLibraryItem): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'access_type' => 'required|string|in:buy,rent',
        ]);

        if (! $videoLibraryItem->isStandaloneEnabled()) {
            return back()->with('error', 'Standalone purchase is not available for this video.');
        }

        if ($user->canAccessTier($videoLibraryItem->access_tier)) {
            return redirect()->route('video-library.show', $videoLibraryItem)
                ->with('info', 'This video is already included in your subscription.');
        }

        $accessType = $validated['access_type'];
        $price = $accessType === 'buy'
            ? $videoLibraryItem->buyPrice()
            : $videoLibraryItem->rentPrice();

        if ($accessType === 'rent' && ! $videoLibraryItem->supportsRental()) {
            return back()->with('error', 'Rental is not available for this video.');
        }

        if ($price === null || $price <= 0) {
            return back()->with('error', 'Pricing missing for this selection.');
        }

        if ($accessType === 'buy') {
            $owned = $videoLibraryItem->activePurchaseFor($user);
            if ($owned && ! $owned->isRental()) {
                return redirect()->route('video-library.show', $videoLibraryItem)
                    ->with('info', 'You already own this video.');
            }
        }

        if ($accessType === 'rent') {
            $activeRental = $videoLibraryItem->purchases()
                ->where('user_id', $user->id)
                ->where('status', 'paid')
                ->where('access_type', 'rent')
                ->where(function ($query) {
                    $query->whereNull('rental_expires_at')
                        ->orWhere('rental_expires_at', '>', now());
                })
                ->first();

            if ($activeRental) {
                return redirect()->route('video-library.show', $videoLibraryItem)
                    ->with('info', 'You already have an active rental for this video.');
            }
        }

        $amount = (int) round($price * 100);
        $currency = 'usd';

        $purchase = VideoPurchase::create([
            'user_id' => $user->id,
            'video_library_item_id' => $videoLibraryItem->id,
            'access_type' => $accessType,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
        ]);

        $secret = config('services.stripe.secret');
        if (! $secret) {
            return back()->with('error', 'Stripe is not configured.');
        }

        $stripe = new \Stripe\StripeClient($secret);

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $videoLibraryItem->title,
                        'description' => Str::limit($videoLibraryItem->description ?? $videoLibraryItem->body, 120),
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'customer_email' => $user->email,
            'success_url' => route('video-library.purchase.success', $videoLibraryItem) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('video-library.purchase.cancel', $videoLibraryItem),
            'metadata' => [
                'video_id' => (string) $videoLibraryItem->id,
                'user_id' => (string) $user->id,
                'access_type' => $accessType,
                'purchase_id' => (string) $purchase->id,
            ],
        ]);

        $purchase->update([
            'stripe_session_id' => $session->id,
        ]);

        return redirect($session->url);
    }

    public function success(Request $request, VideoLibraryItem $videoLibraryItem): RedirectResponse
    {
        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return redirect()->route('video-library.show', $videoLibraryItem)->with('error', 'Missing checkout session.');
        }

        $secret = config('services.stripe.secret');
        if (! $secret) {
            return redirect()->route('video-library.show', $videoLibraryItem)
                ->with('error', 'Stripe is not configured.');
        }
        $stripe = new \Stripe\StripeClient($secret);

        $session = $stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['payment_intent'],
        ]);

        if ($session->payment_status === 'paid' || ($session->status ?? null) === 'complete') {
            $purchase = VideoPurchase::where('stripe_session_id', $session->id)
                ->where('video_library_item_id', $videoLibraryItem->id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (! $purchase) {
                return redirect()->route('video-library.show', $videoLibraryItem)
                    ->with('error', 'Purchase record not found. Please contact support.');
            }

            $purchase->update([
                'status' => 'paid',
                'stripe_payment_intent_id' => optional($session->payment_intent)->id,
                'purchased_at' => now(),
                'rental_expires_at' => $purchase->access_type === 'rent'
                    ? now()->addHours($videoLibraryItem->rentalDurationHours())
                    : null,
            ]);

            return redirect()->route('video-library.show', $videoLibraryItem)
                ->with('success', 'Payment successful! Your access is unlocked.');
        }

        return redirect()->route('video-library.show', $videoLibraryItem)
            ->with('error', 'Payment not completed.');
    }

    public function cancel(VideoLibraryItem $videoLibraryItem): RedirectResponse
    {
        return redirect()->route('video-library.show', $videoLibraryItem)
            ->with('info', 'Purchase cancelled.');
    }
}
