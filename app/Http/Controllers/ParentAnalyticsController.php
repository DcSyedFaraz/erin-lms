<?php

namespace App\Http\Controllers;

use App\Models\ChildProfile;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ParentAnalyticsController extends Controller
{
    protected function assertChildBelongsToUser(Request $request, ChildProfile $child): void
    {
        abort_unless($child->user_id === $request->user()->id, 404);
    }

    public function show(Request $request, ChildProfile $child)
    {
        $this->assertChildBelongsToUser($request, $child);

        $attempts = QuizAttempt::with(['module.course', 'answers.quiz'])
            ->where('child_profile_id', $child->id)
            ->orderByDesc('created_at')
            ->get();

        // Group by course and module
        $byCourse = [];
        foreach ($attempts as $attempt) {
            $courseId = $attempt->course_id;
            $moduleId = $attempt->module_id;
            $byCourse[$courseId]['course'] = $attempt->course ?? Course::find($courseId);
            $byCourse[$courseId]['modules'][$moduleId]['module'] = $attempt->module ?? Module::find($moduleId);
            $byCourse[$courseId]['modules'][$moduleId]['attempts'][] = $attempt;
        }

        return view('parent.children.analytics', compact('child', 'byCourse'));
    }

    public function exportCsv(Request $request, ChildProfile $child)
    {
        $this->assertChildBelongsToUser($request, $child);

        $attempts = QuizAttempt::with(['module.course', 'answers.quiz'])
            ->where('child_profile_id', $child->id)
            ->orderBy('course_id')
            ->orderBy('module_id')
            ->orderBy('id')
            ->get();

        $filename = 'analytics_'.$child->id.'_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function() use ($attempts) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fwrite($out, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($out, [
                'RowType', 'Course', 'Module #', 'Module', 'Attempt ID', 'Status', 'Started At', 'Completed At', 'Total Points', 'Max Points', 'Question ID', 'Question', 'Selected', 'Correct Answer', 'Is Correct', 'Points Awarded'
            ]);

            foreach ($attempts as $att) {
                $courseTitle = optional($att->course)->title;
                $moduleTitle = optional($att->module)->title;
                $moduleOrder = optional($att->module)->order;
                // Attempt row
                fputcsv($out, [
                    'attempt', $courseTitle, $moduleOrder, $moduleTitle, $att->id, $att->status, optional($att->started_at)->toDateTimeString(), optional($att->completed_at)->toDateTimeString(), $att->total_points, $att->max_points, '', '', '', '', '', ''
                ]);
                // Answers rows
                foreach ($att->answers as $ans) {
                    $quiz = $ans->quiz ?: Quiz::find($ans->quiz_id);
                    fputcsv($out, [
                        'answer', $courseTitle, $moduleOrder, $moduleTitle, $att->id, $att->status, optional($att->started_at)->toDateTimeString(), optional($att->completed_at)->toDateTimeString(), $att->total_points, $att->max_points, $ans->quiz_id, optional($quiz)->question, $ans->selected_answer, optional($quiz)->answer, $ans->is_correct ? 'Yes' : 'No', $ans->points_awarded,
                    ]);
                }
            }

            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request, ChildProfile $child)
    {
        $this->assertChildBelongsToUser($request, $child);

        $attempts = QuizAttempt::with(['module.course', 'answers.quiz'])
            ->where('child_profile_id', $child->id)
            ->orderByDesc('created_at')
            ->get();

        $html = view('parent.children._analytics_pdf', [
            'child' => $child,
            'attempts' => $attempts,
        ])->render();

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="analytics_'.$child->id.'.pdf"',
        ]);
    }

    public function overall(Request $request)
    {
        // dd($request->user());
        $user = $request->user();
        $children = $user->childProfiles()->get();
        $childIds = $children->pluck('id');

        $attempts = QuizAttempt::with(['child', 'module.course', 'answers.quiz'])
            ->whereIn('child_profile_id', $childIds)
            ->orderByDesc('created_at')
            ->get();

        $summary = [
            'total_attempts' => $attempts->count(),
            'total_answers' => 0,
            'correct_answers' => 0,
            'total_points' => (int) $attempts->sum('total_points'),
            'max_points' => (int) $attempts->sum('max_points'),
            'avg_percent' => null,
        ];
        foreach ($attempts as $att) {
            $summary['total_answers'] += $att->answers->count();
            $summary['correct_answers'] += $att->answers->where('is_correct', true)->count();
        }
        if ($summary['max_points'] > 0) {
            $summary['avg_percent'] = round(($summary['total_points'] / $summary['max_points']) * 100);
        }

        // Group attempts by child -> course -> module
        $byChild = [];
        foreach ($attempts as $att) {
            $cid = $att->child_profile_id;
            $byChild[$cid]['child'] = $att->child ?: ChildProfile::find($cid);
            $courseId = $att->course_id;
            $moduleId = $att->module_id;
            $byChild[$cid]['courses'][$courseId]['course'] = $att->course ?: Course::find($courseId);
            $byChild[$cid]['courses'][$courseId]['modules'][$moduleId]['module'] = $att->module ?: Module::find($moduleId);
            $byChild[$cid]['courses'][$courseId]['modules'][$moduleId]['attempts'][] = $att;
        }

        return view('parent.children.analytics_overall', compact('children', 'byChild', 'summary'));
    }

    public function exportCsvAll(Request $request)
    {
        $user = $request->user();
        $children = $user->childProfiles()->get();
        $childIds = $children->pluck('id');

        $attempts = QuizAttempt::with(['child', 'module.course', 'answers.quiz'])
            ->whereIn('child_profile_id', $childIds)
            ->orderBy('child_profile_id')
            ->orderBy('course_id')
            ->orderBy('module_id')
            ->orderBy('id')
            ->get();

        $filename = 'analytics_all_children_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function() use ($attempts) {
            $out = fopen('php://output', 'w');
            fwrite($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, [
                'Child', 'RowType', 'Course', 'Module #', 'Module', 'Attempt ID', 'Status', 'Started At', 'Completed At', 'Total Points', 'Max Points', 'Question ID', 'Question', 'Selected', 'Correct Answer', 'Is Correct', 'Points Awarded'
            ]);

            foreach ($attempts as $att) {
                $childName = optional($att->child)->name;
                $courseTitle = optional($att->course)->title;
                $moduleTitle = optional($att->module)->title;
                $moduleOrder = optional($att->module)->order;
                fputcsv($out, [
                    $childName, 'attempt', $courseTitle, $moduleOrder, $moduleTitle, $att->id, $att->status, optional($att->started_at)->toDateTimeString(), optional($att->completed_at)->toDateTimeString(), $att->total_points, $att->max_points, '', '', '', '', '', ''
                ]);
                foreach ($att->answers as $ans) {
                    $quiz = $ans->quiz ?: Quiz::find($ans->quiz_id);
                    fputcsv($out, [
                        $childName, 'answer', $courseTitle, $moduleOrder, $moduleTitle, $att->id, $att->status, optional($att->started_at)->toDateTimeString(), optional($att->completed_at)->toDateTimeString(), $att->total_points, $att->max_points, $ans->quiz_id, optional($quiz)->question, $ans->selected_answer, optional($quiz)->answer, $ans->is_correct ? 'Yes' : 'No', $ans->points_awarded,
                    ]);
                }
            }
            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportPdfAll(Request $request)
    {
        $user = $request->user();
        $children = $user->childProfiles()->get();
        $childIds = $children->pluck('id');

        $attempts = QuizAttempt::with(['child', 'module.course', 'answers.quiz'])
            ->whereIn('child_profile_id', $childIds)
            ->orderByDesc('created_at')
            ->get();

        $html = view('parent.children._analytics_pdf_all', [
            'attempts' => $attempts,
            'generatedAt' => now(),
        ])->render();

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="analytics_all_children.pdf"',
        ]);
    }
}
