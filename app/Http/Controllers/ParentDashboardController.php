<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePurchase;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $ownedCourseIds = CoursePurchase::where('user_id', $user->id)
            ->where('status', 'paid')
            ->pluck('course_id');

        $ownedCount = $ownedCourseIds->count();
        $totalSpentCents = CoursePurchase::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');
        $totalSpent = $totalSpentCents / 100;

        $availableCount = Course::where('status', 'published')
            ->whereNotNull('price')
            ->where('price', '>', 0)
            ->when($ownedCourseIds->isNotEmpty(), fn($q) => $q->whereNotIn('id', $ownedCourseIds))
            ->count();

        $recentPurchases = CoursePurchase::with('course')
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->latest('purchased_at')
            ->take(5)
            ->get();

        $suggestedCourses = Course::where('status', 'published')
            ->whereNotNull('price')
            ->where('price', '>', 0)
            ->when($ownedCourseIds->isNotEmpty(), fn($q) => $q->whereNotIn('id', $ownedCourseIds))
            ->latest('id')
            ->take(6)
            ->get();

        return view('parent.dashboard', [
            'ownedCount' => $ownedCount,
            'totalSpent' => $totalSpent,
            'availableCount' => $availableCount,
            'recentPurchases' => $recentPurchases,
            'suggestedCourses' => $suggestedCourses,
        ]);
    }
}
