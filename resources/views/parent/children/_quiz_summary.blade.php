@php($percent = $attempt->max_points > 0 ? round(($attempt->total_points / $attempt->max_points) * 100) : 0)
<div class="card border-0 shadow-sm">
    <div class="card-body text-center">
        <div class="mb-3"><i class="fas fa-trophy fa-3x text-warning"></i></div>
        <h4 class="mb-1">Great job!</h4>
        <p class="text-muted mb-3">You finished the quiz for <strong>{{ $module->title }}</strong></p>
        <div class="d-flex justify-content-center align-items-center mb-3">
            <span class="badge badge-success mr-2" style="font-size: 1rem;">
                <i class="fas fa-star mr-1"></i>{{ $attempt->total_points }} / {{ $attempt->max_points }} pts
            </span>
            <span class="badge badge-info" style="font-size: 1rem;">
                {{ $percent }}%
            </span>
        </div>
        <div class="progress mb-3" style="height: 12px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <button class="btn btn-outline-primary" id="summaryBackBtn"
            data-module-url="{{ route('parent.children.course.module', ['child' => $attempt->child_profile_id, 'course' => $attempt->course_id, 'module' => $attempt->module_id]) }}">
            <i class="fas fa-arrow-left mr-1"></i> Back to Module
        </button>
    </div>
</div>
