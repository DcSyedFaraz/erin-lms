@extends('parent.layout.app')

@section('content')
<div class="container-fluid">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <h3 class="mb-0"><i class="fas fa-chart-pie mr-2"></i>All Children Analytics</h3>
      <div>
        <a class="btn btn-outline-secondary mr-2" href="{{ route('parent.children.analytics.overall.csv') }}"><i class="fas fa-file-csv mr-1"></i>Export CSV</a>
        <a class="btn btn-outline-secondary" href="{{ route('parent.children.analytics.overall.pdf') }}"><i class="fas fa-file-pdf mr-1"></i>Export PDF</a>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-3 col-6">
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ $summary['total_attempts'] }}</h3>
          <p>Total Attempts</p>
        </div>
        <div class="icon"><i class="fas fa-redo"></i></div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ $summary['correct_answers'] }}/{{ $summary['total_answers'] }}</h3>
          <p>Correct / Answers</p>
        </div>
        <div class="icon"><i class="fas fa-check"></i></div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="small-box bg-primary">
        <div class="inner">
          <h3>{{ $summary['total_points'] }}/{{ $summary['max_points'] }}</h3>
          <p>Total Points</p>
        </div>
        <div class="icon"><i class="fas fa-trophy"></i></div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $summary['avg_percent'] ?? '—' }}@if(!is_null($summary['avg_percent']))%@endif</h3>
          <p>Avg Score</p>
        </div>
        <div class="icon"><i class="fas fa-chart-line"></i></div>
      </div>
    </div>
  </div>

  <div class="card card-outline card-primary">
    <div class="card-header"><h5 class="mb-0">Per Child Details</h5></div>
    <div class="card-body">
      @forelse($byChild as $childId => $block)
        @php($child = $block['child'])
        <div class="mb-4">
          <h5 class="mb-2">
            <i class="fas fa-child mr-2"></i>{{ $child?->name ?? ('Child #'.$childId) }}
            <a href="{{ route('parent.children.analytics', ['child' => $childId]) }}" class="btn btn-xs btn-outline-primary ml-2">View Full</a>
          </h5>
          @foreach(($block['courses'] ?? []) as $courseBlock)
            @php($course = $courseBlock['course'])
            <div class="mb-2">
              <strong><i class="fas fa-book mr-1"></i>{{ $course?->title ?? 'Course' }}</strong>
              <div class="table-responsive mt-2">
                <table class="table table-sm table-striped">
                  <thead>
                    <tr>
                      <th>Module</th>
                      <th>Attempts</th>
                      <th>Best</th>
                      <th>Max</th>
                      <th>Last Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach(($courseBlock['modules'] ?? []) as $moduleBlock)
                      @php($module = $moduleBlock['module'])
                      @php($atts = collect($moduleBlock['attempts'] ?? []))
                      @php($best = (int) $atts->max('total_points'))
                      @php($maxPts = (int) $atts->first()?->max_points ?? 0)
                      @php($last = $atts->first())
                      <tr>
                        <td>#{{ $module?->order }} {{ $module?->title }}</td>
                        <td>{{ $atts->count() }}</td>
                        <td>{{ $best }}</td>
                        <td>{{ $maxPts }}</td>
                        <td>{{ $last?->status }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endforeach
        </div>
        <hr/>
      @empty
        <div class="text-muted">No activity across your children yet.</div>
      @endforelse
    </div>
  </div>
</div>
@endsection

