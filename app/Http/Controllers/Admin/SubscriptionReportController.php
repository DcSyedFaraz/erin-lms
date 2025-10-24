<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $query = DB::table('subscriptions as s')
            ->leftJoin('users as u', 'u.id', '=', 's.user_id')
            ->leftJoin('subscription_plans as p', 'p.stripe_price_id', '=', 's.stripe_price')
            ->select(
                's.id', 's.user_id', 's.type', 's.stripe_id', 's.stripe_status', 's.stripe_price',
                's.quantity', 's.trial_ends_at', 's.ends_at', 's.created_at', 's.updated_at',
                'u.name as user_name', 'u.email as user_email',
                'p.name as plan_name', 'p.interval as plan_interval', 'p.price as plan_price'
            )
            ->orderByDesc('s.id');

        if ($status !== '') {
            $query->where('s.stripe_status', $status);
        }

        $subscriptions = $query->paginate(25)->withQueryString();

        return view('admin.subscriptions.index', compact('subscriptions', 'status'));
    }

    public function stats()
    {
        // Subscriptions stats
        $activeStatuses = ['active', 'trialing'];
        $activeCount = DB::table('subscriptions')->whereIn('stripe_status', $activeStatuses)->count();
        $canceledCount = DB::table('subscriptions')->where('stripe_status', 'canceled')->orWhereNotNull('ends_at')->count();
        $uniqueSubscribers = DB::table('subscriptions')->distinct('user_id')->count('user_id');

        $activeSubs = DB::table('subscriptions as s')
            ->leftJoin('subscription_plans as p', 'p.stripe_price_id', '=', 's.stripe_price')
            ->whereIn('s.stripe_status', $activeStatuses)
            ->get(['p.price', 'p.interval']);

        $mrr = 0.0;
        foreach ($activeSubs as $row) {
            if ($row->price === null) continue;
            if ($row->interval === 'year') {
                $mrr += ((float) $row->price) / 12.0;
            } else {
                $mrr += (float) $row->price;
            }
        }

        // Course stats
        $courseTotals = DB::table('courses')->count();
        $coursePublished = DB::table('courses')->where('status', 'published')->count();
        $coursePurchases = DB::table('course_purchases')->where('status', 'paid')->count();
        $courseRevenueCents = (int) DB::table('course_purchases')->where('status', 'paid')->sum('amount');
        $courseRevenue = $courseRevenueCents / 100.0;

        return view('admin.subscriptions.stats', [
            'activeCount' => $activeCount,
            'canceledCount' => $canceledCount,
            'uniqueSubscribers' => $uniqueSubscribers,
            'mrr' => $mrr,
            'courseTotals' => $courseTotals,
            'coursePublished' => $coursePublished,
            'coursePurchases' => $coursePurchases,
            'courseRevenue' => $courseRevenue,
        ]);
    }
}

