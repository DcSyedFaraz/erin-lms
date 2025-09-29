@php($total = count($attempt->question_order ?? []))
@php($current = min($attempt->current_index + 1, $total))
@php($endsAt = optional($attempt->endsAt()))

<div class="quiz-wrapper" id="quizWrapper"
    data-attempt-id="{{ $attempt->id }}"
    data-answer-url="{{ route('parent.children.quiz.answer', ['child' => $child->id, 'course' => $course->id, 'module' => $module->id]) }}"
    data-complete-url="{{ route('parent.children.quiz.complete', ['child' => $child->id, 'course' => $course->id, 'module' => $module->id]) }}"
    data-ends-at="{{ $endsAt ? $endsAt->toIso8601String() : '' }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1">
                <i class="fas fa-puzzle-piece text-primary mr-2"></i>
                Quiz: {{ $module->title }}
            </h5>
            <small class="text-muted">Question <span id="quizProgressCurrent">{{ $current }}</span> of <span id="quizProgressTotal">{{ $total }}</span></small>
        </div>
        <div class="text-right">
            <div class="mb-1">
                <span class="badge badge-warning" id="quizTimer" title="Time remaining">
                    <i class="fas fa-hourglass-half mr-1"></i>
                    30:00
                </span>
                <span class="badge badge-success ml-2" id="pointsDisplay" title="Total points">
                    <i class="fas fa-star mr-1"></i>
                    <span id="pointsNow">{{ $attempt->total_points }}</span>/<span id="pointsMax">{{ $attempt->max_points }}</span>
                </span>
            </div>
            <button class="btn btn-sm btn-outline-light" id="backToModuleBtn"
                data-module-url="{{ route('parent.children.course.module', ['child' => $child->id, 'course' => $course->id, 'module' => $module->id]) }}">
                <i class="fas fa-arrow-left mr-1"></i> Back to Module
            </button>
        </div>
    </div>

    <div id="quizQuestionContainer">
        @include('parent.children._quiz_question', ['attempt' => $attempt, 'question' => $currentQuestion])
    </div>
</div>

<style>
    /* Quiz option theming */
    .quiz-wrapper .quiz-option {
        border-width: 2px !important;
        transition: all .15s ease-in-out;
    }
    body.dark-mode .quiz-wrapper .quiz-option {
        color: #e2e8f0;
        border-color: #4a5568 !important;
        background: rgba(255,255,255,0.02);
    }
    body.light-mode .quiz-wrapper .quiz-option {
        color: #2d3748;
        border-color: #cbd5e0 !important;
        background: #fff;
    }
    .quiz-wrapper .quiz-option:hover {
        transform: translateY(-1px);
    }
    .quiz-wrapper .quiz-option.active {
        color: #fff !important;
        border-color: transparent !important;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        box-shadow: 0 6px 18px rgba(102, 126, 234, .35);
    }

    /* Badges contrast tweak in dark mode */
    body.dark-mode .quiz-wrapper #quizTimer.badge-warning { color: #1a202c; }
</style>
