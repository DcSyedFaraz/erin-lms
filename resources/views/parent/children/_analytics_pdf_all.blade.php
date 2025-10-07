<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h1,h2,h3,h4 { margin: 0 0 6px 0; }
    .child { margin: 10px 0; }
    .course { margin-left: 10px; }
    .module { margin-left: 20px; }
    table { width:100%; border-collapse: collapse; margin-top: 6px; }
    th, td { border:1px solid #ccc; padding:4px; text-align:left; }
    th { background:#f0f0f0; }
  </style>
  <title>All Children Analytics</title>
</head>
<body>
  <h2>All Children Analytics</h2>
  <small>Generated at {{ $generatedAt->toDayDateTimeString() }}</small>

  @php($byChild = $attempts->groupBy('child_profile_id'))
  @foreach($byChild as $childId => $childAttempts)
    @php($childName = optional($childAttempts->first()->child)->name)
    <div class="child">
      <h3>{{ $childName ?? ('Child #'.$childId) }}</h3>
      @foreach($childAttempts->groupBy('course_id') as $courseId => $courseAttempts)
        @php($course = optional($courseAttempts->first())->course)
        <div class="course">
          <h4>{{ $course?->title ?? 'Course #'.$courseId }}</h4>
          @foreach($courseAttempts->groupBy('module_id') as $moduleId => $modAttempts)
            @php($module = optional($modAttempts->first())->module)
            <div class="module">
              <h4>Module #{{ $module?->order }}: {{ $module?->title }}</h4>
              <table>
                <thead>
                  <tr>
                    <th>Attempt</th>
                    <th>Status</th>
                    <th>Started</th>
                    <th>Completed</th>
                    <th>Score</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($modAttempts as $att)
                    <tr>
                      <td>#{{ $att->id }}</td>
                      <td>{{ $att->status }}</td>
                      <td>{{ optional($att->started_at)->toDateTimeString() }}</td>
                      <td>{{ optional($att->completed_at)->toDateTimeString() }}</td>
                      <td>{{ $att->total_points }}/{{ $att->max_points }}</td>
                    </tr>
                    <tr>
                      <td colspan="5">
                        <strong>Answers:</strong>
                        <ol>
                          @foreach($att->answers as $ans)
                            <li>
                              <em>{{ optional($ans->quiz)->question ?? ('Q#'.$ans->quiz_id) }}</em><br/>
                              Selected: {{ $ans->selected_answer }} | Correct: {{ optional($ans->quiz)->answer }} | Result: {{ $ans->is_correct ? 'Correct' : 'Wrong' }} | Points: {{ $ans->points_awarded }}
                            </li>
                          @endforeach
                        </ol>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endforeach
        </div>
      @endforeach
    </div>
  @endforeach
</body>
</html>

