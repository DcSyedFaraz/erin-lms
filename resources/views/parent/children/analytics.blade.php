@extends('parent.layout.app')

@section('content')
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <h3 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>Analytics for {{ $child->name }}</h3>
      <div>
        <a class="btn btn-outline-secondary mr-2" href="{{ route('parent.children.analytics.csv', $child) }}"><i class="fas fa-file-csv mr-1"></i>Export CSV</a>
        <a class="btn btn-outline-secondary" href="{{ route('parent.children.analytics.pdf', $child) }}"><i class="fas fa-file-pdf mr-1"></i>Export PDF</a>
      </div>
    </div>
  </div>

  @forelse($byCourse as $courseBlock)
  @php($course = $courseBlock['course'])
  <div class="card card-outline card-primary mb-4">
    <div class="card-header">
      <h5 class="mb-0"><i class="fas fa-book mr-2"></i>{{ $course?->title ?? 'Course #'.$loop->index }}</h5>
    </div>
    <div class="card-body">
      @foreach(($courseBlock['modules'] ?? []) as $moduleBlock)
        @php($module = $moduleBlock['module'])
        <div class="mb-3">
          <h6 class="mb-2"><i class="fas fa-list-ol mr-2"></i>Module #{{ $module?->order }}: {{ $module?->title }}</h6>
          <div class="table-responsive">
            <table class="table table-sm table-striped">
              <thead>
                <tr>
                  <th>Attempt ID</th>
                  <th>Status</th>
                  <th>Started</th>
                  <th>Completed</th>
                  <th>Score</th>
                  <th>Answers</th>
                </tr>
              </thead>
              <tbody>
                @foreach(($moduleBlock['attempts'] ?? []) as $att)
                  <tr>
                    <td>#{{ $att->id }}</td>
                    <td><span class="badge badge-{{ $att->status === 'completed' ? 'success' : ($att->status === 'expired' ? 'warning' : 'info') }}">{{ $att->status }}</span></td>
                    <td>{{ optional($att->started_at)->toDayDateTimeString() }}</td>
                    <td>{{ optional($att->completed_at)->toDayDateTimeString() }}</td>
                    <td>{{ $att->total_points }}/{{ $att->max_points }}</td>
                    <td>
                      <details>
                        <summary>View answers</summary>
                        <ul class="mb-0">
                          @foreach($att->answers as $ans)
                            <li>
                              <strong>Q:</strong> {{ $ans->quiz->question ?? ('#'.$ans->quiz_id) }}<br>
                              <strong>Selected:</strong> {{ $ans->selected_answer }}
                              @if($ans->quiz)
                                | <strong>Correct:</strong> {{ $ans->quiz->answer }}
                              @endif
                              | <strong>Result:</strong> {{ $ans->is_correct ? 'Correct' : 'Wrong' }}
                              | <strong>Points:</strong> {{ $ans->points_awarded }}
                            </li>
                          @endforeach
                        </ul>
                      </details>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  @empty
    <div class="alert alert-info">No quiz activity yet for {{ $child->name }}.</div>
  @endforelse
</div>
@endsection
