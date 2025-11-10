<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function program()
    {
        return view('program');
    }

    public function blog()
    {
        return view('blog');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function membership()
    {
        $plans = SubscriptionPlan::with('features')->ordered()->get();
        $user = Auth::user();
        $currentPriceId = $user?->subscription('default')?->stripe_price;
        return view('membership', compact('plans', 'currentPriceId'));
    }

    public function lesson()
    {
        return view('lesson');
    }

    public function dashboard()
    {
        if (Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        } else if (Auth::user()->hasRole('Parent')) {
            return redirect()->route('parent.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
}
