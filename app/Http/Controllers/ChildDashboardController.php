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

        $course->load(['modules.contents', 'modules.quizzes']);

        // Build child-friendly analytics for modules and course
        $moduleIds = $course->modules->pluck('id')->all();
        $attempts = \App\Models\QuizAttempt::where('child_profile_id', $child->id)
            ->whereIn('module_id', $moduleIds)
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [];
        foreach ($course->modules as $m) {
            $modsAttempts = $attempts->where('module_id', $m->id);
            $count = $modsAttempts->count();
            $last = $modsAttempts->first();
            $best = $modsAttempts->max('total_points') ?? 0;
            $maxPts = (int) $m->quizzes->sum('points');
            $lastPercent = ($last && $maxPts > 0) ? round(($last->total_points / $maxPts) * 100) : null;
            $bestPercent = $maxPts > 0 ? round(($best / $maxPts) * 100) : null;
            $avgPercent = $count > 0 && $maxPts > 0 ? round(($modsAttempts->avg('total_points') / $maxPts) * 100) : null;

            $stats[$m->id] = [
                'attempts' => $count,
                'best_points' => (int) $best,
                'max_points' => $maxPts,
                'best_percent' => $bestPercent,
                'last_points' => $last ? (int) $last->total_points : null,
                'last_percent' => $lastPercent,
                'avg_percent' => $avgPercent,
                'last_status' => $last?->status,
            ];
        }

        // Course-level rollup
        $courseAttempts = $attempts->count();
        $courseBest = 0; $courseMax = 0; $courseSumPercents = 0; $courseCompleted = 0;
        foreach ($course->modules as $m) {
            $courseBest += $stats[$m->id]['best_points'];
            $courseMax += $stats[$m->id]['max_points'];
            if ($stats[$m->id]['avg_percent'] !== null) {
                $courseSumPercents += $stats[$m->id]['avg_percent'];
                $courseCompleted++;
            }
        }
        $courseAvgPercent = $courseCompleted > 0 ? round($courseSumPercents / $courseCompleted) : null;

        session(['active_child_id' => $child->id]);

        return view('parent.children.course', [
            'child' => $child,
            'course' => $course,
            'quizStats' => $stats,
            'courseStats' => [
                'attempts' => $courseAttempts,
                'best_points' => $courseBest,
                'max_points' => $courseMax,
                'avg_percent' => $courseAvgPercent,
            ],
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

        $module->loadMissing(['contents', 'quizzes']);

        // Module stats for this child
        $attempts = \App\Models\QuizAttempt::where('child_profile_id', $child->id)
            ->where('module_id', $module->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $maxPts = (int) $module->quizzes->sum('points');
        $count = $attempts->count();
        $last = $attempts->first();
        $best = (int) ($attempts->max('total_points') ?? 0);
        $stats = [
            'attempts' => $count,
            'best_points' => $best,
            'max_points' => $maxPts,
            'best_percent' => $maxPts ? round(($best / $maxPts) * 100) : null,
            'last_points' => $last ? (int) $last->total_points : null,
            'last_percent' => ($last && $maxPts) ? round(($last->total_points / $maxPts) * 100) : null,
            'last_status' => $last?->status,
        ];

        // Return partial HTML for injection
        return response()->view('parent.children._module_contents', [
            'module' => $module,
            'child' => $child,
            'course' => $course,
            'stats' => $stats,
        ]);
    }
}
