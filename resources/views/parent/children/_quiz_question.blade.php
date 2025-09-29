@php($total = count($attempt->question_order ?? []))
@php($index = $attempt->current_index)
@php($qNum = $index + 1)
@if(!$question)
    <div class="alert alert-info">No more questions.</div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="badge badge-primary">Question {{ $qNum }} of {{ $total }}</div>
                <div class="badge badge-success" title="Points for this question"><i class="fas fa-star mr-1"></i>{{ $question->points }} pts</div>
            </div>
            <h5 class="mb-3">{{ $question->question }}</h5>

            <form id="quizAnswerForm">
                <input type="hidden" name="quiz_id" value="{{ $question->id }}">

                @if($question->type === 'true_false')
                    <div class="btn-group-toggle d-flex mb-3" data-toggle="buttons" role="group" aria-label="True or False">
                        <label class="btn btn-outline-primary flex-fill mr-2 quiz-option">
                            <input type="radio" name="selected_answer" value="true" autocomplete="off"> True
                        </label>
                        <label class="btn btn-outline-primary flex-fill quiz-option">
                            <input type="radio" name="selected_answer" value="false" autocomplete="off"> False
                        </label>
                    </div>
                @elseif($question->type === 'multiple_choice')
                    @php($letters = ['A','B','C','D','E','F','G','H'])
                    @php($idx = 0)
                    <div class="quiz-options btn-group-toggle w-100" data-toggle="buttons" role="group" aria-label="Multiple choice options">
                        @foreach(($question->options ?? []) as $opt)
                            @php($opt = is_string($opt) ? trim($opt) : $opt)
                            @if($opt !== null && $opt !== '')
                                @php($letter = $letters[$idx] ?? chr(65 + $idx))
                                @php($idx++)
                                <div class="mb-2">
                                    <label class="btn btn-outline-primary btn-block text-left quiz-option">
                                        <input type="radio" class="d-none" name="selected_answer" value="{{ $letter }}" autocomplete="off">
                                        <span>{{ $letter }}) {{ $opt }}</span>
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" id="submitAnswerBtn" class="btn btn-primary">
                        Submit & Next <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
