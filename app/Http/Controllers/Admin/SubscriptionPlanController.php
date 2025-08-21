<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
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
            'is_trial' => 'nullable|boolean'
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
        SubscriptionPlan::create([
            'name' => $request->name,
            'stripe_price_id' => $price->id,
            'price' => $request->price,
            'interval' => $request->interval,
            'is_trial' => $request->is_trial ?? false,
        ]);

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
