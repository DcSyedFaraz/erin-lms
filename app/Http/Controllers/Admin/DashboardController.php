<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userCount = User::count();
        $courseCount = Course::count();
        $publishedCourses = Course::where('status', 'published')->count();
        $draftCourses = Course::where('status', 'draft')->count();

        $revenueCents = CoursePurchase::where('status', 'paid')->sum('amount');
        $revenue = $revenueCents / 100;
        $purchasesCount = CoursePurchase::where('status', 'paid')->count();

        $topCourses = Course::withCount(['purchases as purchases_count' => function ($q) {
                $q->where('status', 'paid');
            }])
            ->orderByDesc('purchases_count')
            ->take(5)
            ->get();

        $recentPurchases = CoursePurchase::with(['course', 'user'])
            ->where('status', 'paid')
            ->latest('purchased_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'userCount',
            'courseCount',
            'publishedCourses',
            'draftCourses',
            'revenue',
            'purchasesCount',
            'topCourses',
            'recentPurchases'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
