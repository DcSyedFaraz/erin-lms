<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionFeature;
use Illuminate\Http\Request;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\Price;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.plans.index', [
            'plans' => SubscriptionPlan::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'interval' => 'required|in:month,year',
            'is_trial' => 'nullable|boolean',
            'features' => 'array',
            'features.*.name' => 'nullable|string',
            'features.*.value' => 'nullable|string',
            'features.*.sort_order' => 'nullable|integer',
        ]);

        // Set Stripe API Key
        Stripe::setApiKey(config('services.stripe.secret'));

        // 1. Create Product in Stripe
        $product = Product::create([
            'name' => $request->name
        ]);

        // 2. Create Price (subscription plan) in Stripe
        $price = Price::create([
            'unit_amount' => $request->price * 100, // cents
            'currency' => 'usd',
            'recurring' => ['interval' => $request->interval],
            'product' => $product->id,
        ]);

        // 3. Save to local DB
        $plan = SubscriptionPlan::create([
            'name' => $request->name,
            'stripe_price_id' => $price->id,
            'price' => $request->price,
            'interval' => $request->interval,
            'is_trial' => $request->is_trial ?? false,
        ]);

        // Save any provided features
        $features = collect($request->input('features', []))
            ->filter(fn($f) => isset($f['name']) && trim((string) $f['name']) !== '')
            ->values();

        foreach ($features as $index => $f) {
            SubscriptionFeature::create([
                'subscription_plan_id' => $plan->id,
                'name' => $f['name'],
                'value' => $f['value'] ?? null,
                'sort_order' => $f['sort_order'] ?? $index,
            ]);
        }

        return redirect()->route('plans.index')->with('success', 'Plan created in Stripe and LMS');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubscriptionPlan $plan)
    {
        $plan->load('features');
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscriptionPlan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'interval' => 'required|in:month,year',
            'is_trial' => 'nullable|boolean',
            'stripe_price_id' => 'nullable|string',
            'features' => 'array',
            'features.*.id' => 'nullable|integer|exists:subscription_features,id',
            'features.*.name' => 'nullable|string',
            'features.*.value' => 'nullable|string',
            'features.*.sort_order' => 'nullable|integer',
            'feature_delete_ids' => 'array',
            'feature_delete_ids.*' => 'integer|exists:subscription_features,id',
        ]);

        // Sync changes to Stripe (product name and pricing)
        Stripe::setApiKey(config('services.stripe.secret'));
        $currentStripePriceId = $plan->stripe_price_id;
        $currentPriceObj = $currentStripePriceId ? Price::retrieve($currentStripePriceId) : null;
        $productId = $currentPriceObj?->product;

        if ($productId) {
            // Update product name if changed
            if ($plan->name !== $data['name']) {
                $product = Product::retrieve($productId);
                $product->name = $data['name'];
                $product->save();
            }
            // Allow manual relink to an existing price if explicitly provided
            if (!empty($data['stripe_price_id'])) {
                $plan->stripe_price_id = $data['stripe_price_id'];
            }
            // If price or interval changed, create a new price and deactivate old one
            $priceChanged = ((float) $plan->price !== (float) $data['price']) || ($plan->interval !== $data['interval']);
            if ($priceChanged) {
                $newPrice = Price::create([
                    'unit_amount' => (int) round(((float) $data['price']) * 100),
                    'currency' => 'usd',
                    'recurring' => ['interval' => $data['interval']],
                    'product' => $productId,
                ]);

                if ($currentPriceObj) {
                    $currentPriceObj->active = false;
                    $currentPriceObj->save();
                }

                $plan->stripe_price_id = $newPrice->id;
            }
        }

        // Update local plan data
        $plan->name = $data['name'];
        $plan->price = $data['price'];
        $plan->interval = $data['interval'];
        $plan->is_trial = $request->boolean('is_trial');



        $plan->save();

        $deleteIds = collect($request->input('feature_delete_ids', []))->filter()->all();
        if (!empty($deleteIds)) {
            SubscriptionFeature::where('subscription_plan_id', $plan->id)
                ->whereIn('id', $deleteIds)
                ->delete();
        }

        $features = collect($request->input('features', []));
        foreach ($features as $index => $f) {
            $name = isset($f['name']) ? trim((string) $f['name']) : '';
            if ($name === '') {
                continue;
            }
            $payload = [
                'subscription_plan_id' => $plan->id,
                'name' => $name,
                'value' => $f['value'] ?? null,
                'sort_order' => $f['sort_order'] ?? $index,
            ];
            if (!empty($f['id'])) {
                SubscriptionFeature::where('id', (int) $f['id'])
                    ->where('subscription_plan_id', $plan->id)
                    ->update($payload);
            } else {
                SubscriptionFeature::create($payload);
            }
        }

        return redirect()->route('plans.index')->with('success', 'Plan and features updated and synced to Stripe.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscriptionPlan $plan)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // 1. Deactivate Price
        if ($plan->stripe_price_id) {
            $stripePrice = Price::retrieve($plan->stripe_price_id);
            $stripePrice->active = false;
            $stripePrice->save();
        }

        // 2. Deactivate Product
        // You need to retrieve product ID from Stripe or store it in DB
        // We'll assume it's embedded in the price:
        $priceObj = Price::retrieve($plan->stripe_price_id);
        if ($priceObj->product) {
            $stripeProduct = Product::retrieve($priceObj->product);
            $stripeProduct->active = false;
            $stripeProduct->save();
        }

        // 3. Delete from local DB
        $plan->delete();

        return back()->with('success', 'Plan archived in Stripe and deleted from LMS.');
    }
}
