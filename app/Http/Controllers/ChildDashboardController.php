<?php

namespace App\Http\Controllers;

use App\Models\ChildProfile;
use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ChildDashboardController extends Controller
{
    protected function assertChildBelongsToUser(Request $request, ChildProfile $child): void
    {
        abort_unless($child->user_id === $request->user()->id, 404);
    }

    public function dashboard(Request $request, ChildProfile $child): View
    {
        $this->assertChildBelongsToUser($request, $child);

        $purchases = CoursePurchase::with('course')
            ->where('user_id', $request->user()->id)
            ->where('status', 'paid')
            ->latest()
            ->get();

        session(['active_child_id' => $child->id]);

        return view('parent.children.dashboard', [
            'child' => $child,
            'purchases' => $purchases,
        ]);
    }

    public function course(Request $request, ChildProfile $child, Course $course): View|RedirectResponse
    {
        $this->assertChildBelongsToUser($request, $child);

        $hasPurchase = CoursePurchase::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->where('status', 'paid')
            ->exists();

        if (! $hasPurchase) {
            return redirect()->route('parent.children.dashboard', $child)
                ->with('error', 'This course is not available to this child.');
        }

        $course->load(['modules.contents']);

        session(['active_child_id' => $child->id]);

        return view('parent.children.course', [
            'child' => $child,
            'course' => $course,
        ]);
    }

    public function module(Request $request, ChildProfile $child, Course $course, Module $module)
    {
        $this->assertChildBelongsToUser($request, $child);

        // Ensure module belongs to course
        abort_unless($module->course_id === $course->id, 404);

        // Ensure parent has purchased the course
        $hasPurchase = CoursePurchase::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->where('status', 'paid')
            ->exists();

        abort_unless($hasPurchase, 403);

        $module->loadMissing('contents');

        // Return partial HTML for injection
        return response()->view('parent.children._module_contents', [
            'module' => $module,
        ]);
    }
}
