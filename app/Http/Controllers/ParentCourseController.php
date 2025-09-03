<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePurchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ParentCourseController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $purchasedIds = CoursePurchase::where('user_id', $user->id)
            ->where('status', 'paid')
            ->pluck('course_id')
            ->all();

        $courses = Course::query()
            ->where('status', 'published')
            ->when(count($purchasedIds) > 0, fn ($q) => $q->whereNotIn('id', $purchasedIds))
            ->orderByDesc('id')
            ->get();

        return view('parent.courses.index', compact('courses', 'purchasedIds'));
    }

    public function my(Request $request): View
    {
        $user = $request->user();

        $purchases = CoursePurchase::with('course')
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->latest()
            ->get();

        return view('parent.courses.my', compact('purchases'));
    }

    public function summary(Request $request): View
    {
        $user = $request->user();

        $purchasedIds = CoursePurchase::where('user_id', $user->id)
            ->where('status', 'paid')
            ->pluck('course_id')
            ->all();

        $courses = Course::query()
            ->where('status', 'published')
            ->withCount(['modules', 'contents', 'quizzes'])
            ->orderBy('title')
            ->get();

        return view('parent.courses.summary', compact('courses', 'purchasedIds'));
    }

    public function show(Request $request, Course $course): View|RedirectResponse
    {
        $user = $request->user();

        $hasPurchase = CoursePurchase::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'paid')
            ->exists();

        if (! $hasPurchase) {
            return redirect()->route('parent.courses.index')
                ->with('error', 'Please purchase the course to access its content.');
        }

        $course->load(['modules']);

        return view('parent.courses.show', compact('course'));
    }

    public function checkout(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();

        if ($course->price === null || $course->price <= 0) {
            return back()->with('error', 'This course is not purchasable.');
        }

        $alreadyPurchased = CoursePurchase::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'paid')
            ->exists();

        if ($alreadyPurchased) {
            return redirect()->route('parent.courses.my')->with('info', 'You already own this course.');
        }

        $currency = 'usd';

        // Create a pending purchase record
        $purchase = CoursePurchase::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => (int) round($course->price * 100),
            'currency' => $currency,
            'status' => 'pending',
        ]);

        // Create Stripe Checkout Session
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
                        'name' => $course->title,
                        'description' => str($course->description)->limit(120)->toString(),
                    ],
                    'unit_amount' => (int) round($course->price * 100),
                ],
                'quantity' => 1,
            ]],
            'customer_email' => $user->email,
            'success_url' => route('parent.courses.success', $course) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('parent.courses.cancel', $course),
            'metadata' => [
                'course_id' => (string) $course->id,
                'user_id' => (string) $user->id,
                'purchase_id' => (string) $purchase->id,
            ],
        ]);

        $purchase->update([
            'stripe_session_id' => $session->id,
        ]);

        return redirect($session->url);
    }

    public function success(Request $request, Course $course): RedirectResponse
    {
        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return redirect()->route('parent.courses.my')->with('error', 'Missing checkout session.');
        }

        $secret = config('services.stripe.secret');
        $stripe = new \Stripe\StripeClient($secret);

        $session = $stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['payment_intent'],
        ]);

        if ($session->payment_status === 'paid' || ($session->status ?? null) === 'complete') {
            $purchase = CoursePurchase::where('stripe_session_id', $session->id)
                ->where('course_id', $course->id)
                ->first();

            if ($purchase) {
                $purchase->update([
                    'status' => 'paid',
                    'stripe_payment_intent_id' => optional($session->payment_intent)->id,
                    'purchased_at' => now(),
                ]);
            }

            return redirect()->route('parent.courses.my')->with('success', 'Payment successful! You now own this course.');
        }

        return redirect()->route('parent.courses.index')->with('error', 'Payment not completed.');
    }

    public function cancel(Request $request, Course $course): RedirectResponse
    {
        return redirect()->route('parent.courses.index')->with('info', 'Purchase cancelled.');
    }

    // Inline card modal checkout (Stripe Elements)
    public function intent(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();

        if ($course->status !== 'published' || $course->price === null || $course->price <= 0) {
            return response()->json(['message' => 'Course not purchasable'], 422);
        }

        $existing = CoursePurchase::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing && $existing->status === 'paid') {
            return response()->json(['message' => 'Already owned'], 409);
        }

        $currency = 'usd';
        $amount = (int) round($course->price * 100);

        if (! $existing) {
            $existing = CoursePurchase::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'pending',
            ]);
        } else {
            $existing->update([
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'pending',
            ]);
        }

        $secret = config('services.stripe.secret');
        if (! $secret) {
            return response()->json(['message' => 'Stripe not configured'], 500);
        }
        $stripe = new \Stripe\StripeClient($secret);

        $intent = $stripe->paymentIntents->create([
            'amount' => $amount,
            'currency' => $currency,
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'course_id' => (string) $course->id,
                'user_id' => (string) $user->id,
                'purchase_id' => (string) $existing->id,
            ],
        ]);

        return response()->json([
            'client_secret' => $intent->client_secret,
            'purchase_id' => $existing->id,
        ]);
    }

    public function complete(Request $request, Course $course): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'purchase_id' => 'required|integer',
        ]);

        $user = $request->user();

        $purchase = CoursePurchase::where('id', $request->integer('purchase_id'))
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $secret = config('services.stripe.secret');
        $stripe = new \Stripe\StripeClient($secret);
        $intent = $stripe->paymentIntents->retrieve($request->string('payment_intent_id'));

        if ($intent->status === 'succeeded' && $intent->amount_received >= $purchase->amount) {
            $purchase->update([
                'status' => 'paid',
                'stripe_payment_intent_id' => $intent->id,
                'purchased_at' => now(),
            ]);

            return response()->json(['message' => 'Payment successful']);
        }

        return response()->json(['message' => 'Payment not completed'], 422);
    }
}
