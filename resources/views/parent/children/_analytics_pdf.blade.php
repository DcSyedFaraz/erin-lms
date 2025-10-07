<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h1,h2,h3,h4 { margin: 0 0 6px 0; }
    .course { margin-bottom: 16px; }
    .module { margin: 8px 0; }
    table { width:100%; border-collapse: collapse; margin-top: 6px; }
    th, td { border:1px solid #ccc; padding:4px; text-align:left; }
    th { background:#f0f0f0; }
  </style>
  <title>Analytics - {{ $child->name }}</title>
  </head>
<body>
  <h2>Analytics for {{ $child->name }}</h2>
  <small>Generated at {{ now()->toDayDateTimeString() }}</small>

  @php($grouped = $attempts->groupBy('course_id'))
  @foreach($grouped as $courseId => $courseAttempts)
    @php($course = optional($courseAttempts->first())->course)
    <div class="course">
      <h3>{{ $course?->title ?? 'Course #'.$courseId }}</h3>
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
</body>
</html>

