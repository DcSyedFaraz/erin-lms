<?php

namespace App\Http\Controllers;

use App\Models\ChildProfile;
use App\Models\Course;
use App\Models\CoursePurchase;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChildQuizController extends Controller
{
    protected function assertChildBelongsToUser(Request $request, ChildProfile $child): void
    {
        abort_unless($child->user_id === $request->user()->id, 404);
    }

    protected function assertCoursePurchased(Request $request, Course $course): void
    {
        $hasPurchase = CoursePurchase::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->where('status', 'paid')
            ->exists();

        abort_unless($hasPurchase, 403);
    }

    protected function assertModuleInCourse(Module $module, Course $course): void
    {
        abort_unless($module->course_id === $course->id, 404);
    }

    public function start(Request $request, ChildProfile $child, Course $course, Module $module)
    {
        $this->assertChildBelongsToUser($request, $child);
        $this->assertModuleInCourse($module, $course);
        $this->assertCoursePurchased($request, $course);

        $module->loadMissing('quizzes');
        abort_unless($module->quizzes->count() > 0, 404);

        // Try to resume existing in-progress attempt (not expired)
        $attempt = QuizAttempt::where('child_profile_id', $child->id)
            ->where('module_id', $module->id)
            ->whereIn('status', ['in_progress'])
            ->latest('id')
            ->first();

        if ($attempt) {
            $endsAt = $attempt->endsAt();
            if ($endsAt && $endsAt->isPast()) {
                // Mark as expired if time is up
                $attempt->update(['status' => 'expired', 'completed_at' => now()]);
                $attempt = null;
            }
        }

        if (!$attempt) {
            $order = $module->quizzes()->orderBy('id')->pluck('id')->all();
            $maxPoints = (int) $module->quizzes->sum('points');

            $attempt = QuizAttempt::create([
                'child_profile_id' => $child->id,
                'course_id' => $course->id,
                'module_id' => $module->id,
                'started_at' => now(),
                'status' => 'in_progress',
                'total_points' => 0,
                'max_points' => $maxPoints,
                'question_order' => $order,
                'current_index' => 0,
                'time_limit_minutes' => 30,
            ]);
        }

        // Render quiz UI partial
        $currentQuestion = $this->getCurrentQuestion($attempt);

        return response()->view('parent.children._quiz', [
            'child' => $child,
            'course' => $course,
            'module' => $module,
            'attempt' => $attempt,
            'currentQuestion' => $currentQuestion,
        ]);
    }

    public function answer(Request $request, ChildProfile $child, Course $course, Module $module)
    {
        $this->assertChildBelongsToUser($request, $child);
        $this->assertModuleInCourse($module, $course);
        $this->assertCoursePurchased($request, $course);

        $data = $request->validate([
            'attempt_id' => 'required|integer|exists:quiz_attempts,id',
            'quiz_id' => 'required|integer|exists:quizzes,id',
            'selected_answer' => 'required|string',
        ]);

        /** @var QuizAttempt $attempt */
        $attempt = QuizAttempt::where('id', $data['attempt_id'])
            ->where('child_profile_id', $child->id)
            ->where('module_id', $module->id)
            ->firstOrFail();

        // Check time
        $endsAt = $attempt->endsAt();
        if ($endsAt && $endsAt->isPast()) {
            $attempt->update(['status' => 'expired', 'completed_at' => now()]);
            return response()->json([
                'status' => 'expired',
                'message' => 'Time is up. The quiz has ended.',
            ]);
        }

        abort_unless($attempt->status === 'in_progress', 403);

        $order = $attempt->question_order ?? [];
        $index = (int) $attempt->current_index;
        abort_unless(isset($order[$index]) && (int) $order[$index] === (int) $data['quiz_id'], 422);

        $quiz = Quiz::findOrFail($data['quiz_id']);

        // Evaluate with MCQ fallback (letters vs text)
        $selectedRaw = (string) $data['selected_answer'];
        $storedRaw = (string) $quiz->answer;
        $selectedLower = trim(mb_strtolower($selectedRaw));
        $storedLower = trim(mb_strtolower($storedRaw));

        $isCorrect = $selectedLower === $storedLower;

        if (!$isCorrect && $quiz->type === 'multiple_choice') {
            $options = collect($quiz->options ?? [])
                ->filter(fn($v) => is_string($v) && trim($v) !== '')
                ->values()
                ->all();

            // If selected is a letter and stored is text, map letter -> option text
            if (!$isCorrect && strlen($selectedRaw) === 1 && ctype_alpha($selectedRaw) && !empty($options)) {
                $idx = ord(strtoupper($selectedRaw)) - 65; // A=0
                if (isset($options[$idx])) {
                    if (trim(mb_strtolower($options[$idx])) === $storedLower) {
                        $isCorrect = true;
                    }
                }
            }

            // If stored is a letter and selected is text, map text -> letter
            if (!$isCorrect && strlen($storedRaw) === 1 && ctype_alpha($storedRaw) && !empty($options)) {
                $selIndex = null;
                foreach ($options as $ix => $opt) {
                    if (trim(mb_strtolower($opt)) === $selectedLower) { $selIndex = $ix; break; }
                }
                if ($selIndex !== null) {
                    $letter = chr(65 + $selIndex);
                    if (trim(mb_strtolower($letter)) === $storedLower) {
                        $isCorrect = true;
                    }
                }
            }
        }

        $points = $isCorrect ? (int) ($quiz->points ?? 1) : 0;

        DB::transaction(function () use ($attempt, $quiz, $data, $points, $isCorrect) {
            QuizAnswer::updateOrCreate(
                ['quiz_attempt_id' => $attempt->id, 'quiz_id' => $quiz->id],
                [
                    'selected_answer' => $data['selected_answer'],
                    'is_correct' => $isCorrect,
                    'points_awarded' => $points,
                    'answered_at' => now(),
                ]
            );

            $attempt->total_points = (int) $attempt->answers()->sum('points_awarded');

            $attempt->current_index = $attempt->current_index + 1;

            // Complete if last question answered
            if ($attempt->current_index >= count($attempt->question_order ?? [])) {
                $attempt->status = 'completed';
                $attempt->completed_at = now();
            }

            $attempt->save();
        });

        // Prepare next view or summary
        $attempt->refresh();

        if ($attempt->status !== 'in_progress') {
            return response()->json([
                'status' => 'completed',
                'html' => view('parent.children._quiz_summary', [
                    'attempt' => $attempt,
                    'module' => $module,
                ])->render(),
                'progress' => [
                    'current' => count($attempt->question_order ?? []),
                    'total' => count($attempt->question_order ?? []),
                    'points' => $attempt->total_points,
                    'max' => $attempt->max_points,
                ],
            ]);
        }

        $currentQuestion = $this->getCurrentQuestion($attempt);

        return response()->json([
            'status' => 'ok',
            'html' => view('parent.children._quiz_question', [
                'attempt' => $attempt,
                'question' => $currentQuestion,
            ])->render(),
            'progress' => [
                'current' => $attempt->current_index + 1,
                'total' => count($attempt->question_order ?? []),
                'points' => $attempt->total_points,
                'max' => $attempt->max_points,
            ],
        ]);
    }

    protected function getCurrentQuestion(QuizAttempt $attempt): ?Quiz
    {
        $order = $attempt->question_order ?? [];
        $index = (int) $attempt->current_index;
        if (!isset($order[$index])) return null;
        return Quiz::find($order[$index]);
    }

    public function complete(Request $request, ChildProfile $child, Course $course, Module $module)
    {
        $this->assertChildBelongsToUser($request, $child);
        $this->assertModuleInCourse($module, $course);
        $this->assertCoursePurchased($request, $course);

        $data = $request->validate([
            'attempt_id' => 'required|integer|exists:quiz_attempts,id',
        ]);

        /** @var QuizAttempt $attempt */
        $attempt = QuizAttempt::where('id', $data['attempt_id'])
            ->where('child_profile_id', $child->id)
            ->where('module_id', $module->id)
            ->firstOrFail();

        if ($attempt->status === 'in_progress') {
            $attempt->status = 'expired';
            $attempt->completed_at = now();
            $attempt->save();
        }

        $attempt->refresh();
        return response()->json([
            'status' => 'expired',
            'html' => view('parent.children._quiz_summary', [
                'attempt' => $attempt,
                'module' => $module,
            ])->render(),
            'progress' => [
                'current' => min($attempt->current_index, count($attempt->question_order ?? [])),
                'total' => count($attempt->question_order ?? []),
                'points' => $attempt->total_points,
                'max' => $attempt->max_points,
            ],
        ]);
    }
}
