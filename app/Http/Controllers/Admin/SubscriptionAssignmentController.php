<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionAssignmentController extends Controller
{
    public function create()
    {
        return view('admin.subscriptions.assign', [
            'users' => User::orderBy('name')->get(['id','name','email']),
            'plans' => SubscriptionPlan::orderBy('price')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'note' => 'nullable|string',
            'trial_ends_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:today',
        ]);

        $userId = (int) $data['user_id'];
        $plan = SubscriptionPlan::findOrFail((int) $data['subscription_plan_id']);

        // Upsert local subscription record marked active (manual assignment)
        $existing = DB::table('subscriptions')
            ->where('user_id', $userId)
            ->where('type', 'default')
            ->orderByDesc('id')
            ->first();

        if ($existing) {
            DB::table('subscriptions')->where('id', $existing->id)->update([
                'stripe_id' => 'manual_' . $userId . '_' . $plan->id . '_' . uniqid(),
                'stripe_status' => 'active',
                'stripe_price' => $plan->stripe_price_id,
                'quantity' => 1,
                'trial_ends_at' => $data['trial_ends_at'] ?? null,
                'ends_at' => $data['ends_at'] ?? null,
                'updated_at' => now(),
            ]);
            $subscriptionId = $existing->id;
        } else {
            $subscriptionId = DB::table('subscriptions')->insertGetId([
                'user_id' => $userId,
                'type' => 'default',
                'stripe_id' => 'manual_' . $userId . '_' . $plan->id . '_' . uniqid(),
                'stripe_status' => 'active',
                'stripe_price' => $plan->stripe_price_id,
                'quantity' => 1,
                'trial_ends_at' => $data['trial_ends_at'] ?? null,
                'ends_at' => $data['ends_at'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Replace item record for completeness
        DB::table('subscription_items')->where('subscription_id', $subscriptionId)->delete();
        DB::table('subscription_items')->insert([
            'subscription_id' => $subscriptionId,
            'stripe_id' => 'manual_item_' . $userId . '_' . $plan->id . '_' . uniqid(),
            'stripe_product' => 'manual_product',
            'stripe_price' => $plan->stripe_price_id,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.subscriptions.assign.create')
            ->with('success', 'Subscription assigned to user successfully.');
    }
}
