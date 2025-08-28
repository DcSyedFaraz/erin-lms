@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Quiz for {{ $module->title }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" onclick="addNewQuestion()">
                                <i class="fas fa-plus"></i> Add Another Question
                            </button>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('modules.quiz.update', $module) }}" id="quizForm">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div id="questionsContainer">
                                @php $index = 1; @endphp
                                @if($module->quizzes->isEmpty())
                                    <div class="question-block" data-question-id="1">
                                        <div class="card card-outline card-info mb-4">
                                            <div class="card-header">
                                                <h5 class="card-title">Question #1</h5>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(this)" style="display: none;">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <!-- Question Field -->
                                                <div class="form-group">
                                                    <label for="question_1">Question <span class="text-danger">*</span></label>
                                                    <textarea class="form-control" name="questions[1][question]" id="question_1" rows="3" placeholder="Enter your quiz question..." required></textarea>
                                                    <small class="form-text text-muted">Write a clear and concise question for your quiz.</small>
                                                </div>

                                                <!-- Quiz Type Selection -->
                                                <div class="form-group">
                                                    <label for="quiz_type_1">Quiz Type <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="questions[1][type]" id="quiz_type_1" onchange="handleTypeChange(1)" required>
                                                        <option value="">Select Quiz Type</option>
                                                        <option value="multiple_choice">Multiple Choice</option>
                                                        <option value="true_false">True / False</option>
                                                    </select>
                                                    <small class="form-text text-muted">Choose the type of quiz question.</small>
                                                </div>

                                                <!-- Multiple Choice Options -->
                                                <div id="multiple_choice_section_1" style="display: none;">
                                                    <div class="form-group">
                                                        <label>Answer Options <span class="text-danger">*</span></label>
                                                        <small class="form-text text-muted mb-2">Add multiple choice options. At least 2 options are required.</small>

                                                        <div id="options_container_1">
                                                            <div class="input-group mb-2 option-input">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">A)</span>
                                                                </div>
                                                                <input type="text" class="form-control" name="questions[1][options][]" placeholder="Enter option A" oninput="updateAnswerOptions(1)">
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-danger" onclick="removeOption(this, 1)" disabled>
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <div class="input-group mb-2 option-input">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">B)</span>
                                                                </div>
                                                                <input type="text" class="form-control" name="questions[1][options][]" placeholder="Enter option B" oninput="updateAnswerOptions(1)">
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-danger" onclick="removeOption(this, 1)" disabled>
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <button type="button" class="btn btn-secondary btn-sm" onclick="addOption(1)">
                                                            <i class="fas fa-plus"></i> Add Option
                                                        </button>
                                                    </div>

                                                    <!-- Multiple Choice Answer -->
                                                    <div class="form-group">
                                                        <label for="mc_answer_1">Correct Answer <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="questions[1][answer]" id="mc_answer_1">
                                                            <option value="">Select the correct answer</option>
                                                        </select>
                                                        <small class="form-text text-muted">Choose which option is the correct answer.</small>
                                                    </div>
                                                </div>

                                                <!-- True/False Options -->
                                                <div id="true_false_section_1" style="display: none;">
                                                    <div class="form-group">
                                                        <label>Correct Answer <span class="text-danger">*</span></label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="questions[1][answer]" id="true_option_1" value="True">
                                                            <label class="form-check-label" for="true_option_1">
                                                                <i class="fas fa-check text-success"></i> True
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="questions[1][answer]" id="false_option_1" value="False">
                                                            <label class="form-check-label" for="false_option_1">
                                                                <i class="fas fa-times text-danger"></i> False
                                                            </label>
                                                        </div>
                                                        <small class="form-text text-muted">Select whether the statement is true or false.</small>
                                                    </div>
                                                </div>

                                                <!-- Points Field -->
                                                <div class="form-group">
                                                    <label for="points_1">Points</label>
                                                    <input type="number" class="form-control" name="questions[1][points]" id="points_1" value="1" min="1" max="10">
                                                    <small class="form-text text-muted">Points awarded for correct answer (1-10).</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @foreach($module->quizzes as $quiz)
                                    <div class="question-block" data-question-id="{{ $index }}">
                                        <div class="card card-outline card-info mb-4">
                                            <div class="card-header">
                                                <h5 class="card-title">Question #{{ $index }}</h5>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(this)">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $quiz->id }}">
                                                <!-- Question Field -->
                                                <div class="form-group">
                                                    <label for="question_{{ $index }}">Question <span class="text-danger">*</span></label>
                                                    <textarea class="form-control" name="questions[{{ $index }}][question]" id="question_{{ $index }}" rows="3" placeholder="Enter your quiz question..." required>{{ $quiz->question }}</textarea>
                                                    <small class="form-text text-muted">Write a clear and concise question for your quiz.</small>
                                                </div>

                                                <!-- Quiz Type Selection -->
                                                <div class="form-group">
                                                    <label for="quiz_type_{{ $index }}">Quiz Type <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="questions[{{ $index }}][type]" id="quiz_type_{{ $index }}" onchange="handleTypeChange({{ $index }})" required>
                                                        <option value="">Select Quiz Type</option>
                                                        <option value="multiple_choice" {{ $quiz->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                                        <option value="true_false" {{ $quiz->type === 'true_false' ? 'selected' : '' }}>True / False</option>
                                                    </select>
                                                    <small class="form-text text-muted">Choose the type of quiz question.</small>
                                                </div>

                                                <!-- Multiple Choice Options -->
                                                <div id="multiple_choice_section_{{ $index }}" style="display: {{ $quiz->type === 'multiple_choice' ? 'block' : 'none' }};">
                                                    <div class="form-group">
                                                        <label>Answer Options <span class="text-danger">*</span></label>
                                                        <small class="form-text text-muted mb-2">Add multiple choice options. At least 2 options are required.</small>
                                                        <div id="options_container_{{ $index }}">
                                                            @foreach($quiz->options ?? [] as $optIndex => $option)
                                                            <div class="input-group mb-2 option-input">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">{{ chr(65 + $optIndex) }})</span>
                                                                </div>
                                                                <input type="text" class="form-control" name="questions[{{ $index }}][options][]" value="{{ $option }}" oninput="updateAnswerOptions({{ $index }})">
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-danger" onclick="removeOption(this, {{ $index }})" {{ $optIndex < 2 ? 'disabled' : '' }}>
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        <button type="button" class="btn btn-secondary btn-sm" onclick="addOption({{ $index }})">
                                                            <i class="fas fa-plus"></i> Add Option
                                                        </button>
                                                    </div>

                                                    <!-- Multiple Choice Answer -->
                                                    <div class="form-group">
                                                        <label for="mc_answer_{{ $index }}">Correct Answer <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="questions[{{ $index }}][answer]" id="mc_answer_{{ $index }}">
                                                            <option value="">Select the correct answer</option>
                                                            @foreach($quiz->options ?? [] as $optIndex => $option)
                                                                @php $label = chr(65 + $optIndex); @endphp
                                                                <option value="{{ $label }}" {{ $quiz->answer === $label ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                        <small class="form-text text-muted">Choose which option is the correct answer.</small>
                                                    </div>
                                                </div>

                                                <!-- True/False Options -->
                                                <div id="true_false_section_{{ $index }}" style="display: {{ $quiz->type === 'true_false' ? 'block' : 'none' }};">
                                                    <div class="form-group">
                                                        <label>Correct Answer <span class="text-danger">*</span></label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="questions[{{ $index }}][answer]" id="true_option_{{ $index }}" value="True" {{ $quiz->answer === 'True' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="true_option_{{ $index }}">
                                                                <i class="fas fa-check text-success"></i> True
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="questions[{{ $index }}][answer]" id="false_option_{{ $index }}" value="False" {{ $quiz->answer === 'False' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="false_option_{{ $index }}">
                                                                <i class="fas fa-times text-danger"></i> False
                                                            </label>
                                                        </div>
                                                        <small class="form-text text-muted">Select whether the statement is true or false.</small>
                                                    </div>
                                                </div>

                                                <!-- Points Field -->
                                                <div class="form-group">
                                                    <label for="points_{{ $index }}">Points</label>
                                                    <input type="number" class="form-control" name="questions[{{ $index }}][points]" id="points_{{ $index }}" value="{{ $quiz->points }}" min="1" max="10">
                                                    <small class="form-text text-muted">Points awarded for correct answer (1-10).</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php $index++; @endphp
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save All Quiz Questions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let questionCounter = {{ $module->quizzes->isEmpty() ? 1 : $module->quizzes->count() }};
        const maxOptions = 6;
        const optionLabels = ['A', 'B', 'C', 'D', 'E', 'F'];

        document.addEventListener('DOMContentLoaded', function () {
            for (let i = 1; i <= questionCounter; i++) {
                handleTypeChange(i);
                updateAnswerOptions(i);
            }
            updateRemoveButtons();
        });

        function addNewQuestion() {
            questionCounter++;
            const container = document.getElementById('questionsContainer');
            const newQuestion = createQuestionBlock(questionCounter);
            container.appendChild(newQuestion);
            updateRemoveButtons();
            newQuestion.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            toastr.success(`Question #${questionCounter} added successfully!`);
        }

        function createQuestionBlock(questionId) {
            const questionBlock = document.createElement('div');
            questionBlock.className = 'question-block';
            questionBlock.setAttribute('data-question-id', questionId);
            questionBlock.innerHTML = `
        <div class="card card-outline card-info mb-4">
            <div class="card-header">
                <h5 class="card-title">Question #${questionId}</h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(this)">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="question_${questionId}">Question <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="questions[${questionId}][question]" id="question_${questionId}" rows="3" placeholder="Enter your quiz question..." required></textarea>
                    <small class="form-text text-muted">Write a clear and concise question for your quiz.</small>
                </div>
                <div class="form-group">
                    <label for="quiz_type_${questionId}">Quiz Type <span class="text-danger">*</span></label>
                    <select class="form-control" name="questions[${questionId}][type]" id="quiz_type_${questionId}" onchange="handleTypeChange(${questionId})" required>
                        <option value="">Select Quiz Type</option>
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="true_false">True / False</option>
                    </select>
                    <small class="form-text text-muted">Choose the type of quiz question.</small>
                </div>
                <div id="multiple_choice_section_${questionId}" style="display: none;">
                    <div class="form-group">
                        <label>Answer Options <span class="text-danger">*</span></label>
                        <small class="form-text text-muted mb-2">Add multiple choice options. At least 2 options are required.</small>
                        <div id="options_container_${questionId}">
                            <div class="input-group mb-2 option-input">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">A)</span>
                                </div>
                                <input type="text" class="form-control" name="questions[${questionId}][options][]" placeholder="Enter option A" oninput="updateAnswerOptions(${questionId})">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger" onclick="removeOption(this, ${questionId})" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="input-group mb-2 option-input">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">B)</span>
                                </div>
                                <input type="text" class="form-control" name="questions[${questionId}][options][]" placeholder="Enter option B" oninput="updateAnswerOptions(${questionId})">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger" onclick="removeOption(this, ${questionId})" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addOption(${questionId})">
                            <i class="fas fa-plus"></i> Add Option
                        </button>
                    </div>
                    <div class="form-group">
                        <label for="mc_answer_${questionId}">Correct Answer <span class="text-danger">*</span></label>
                        <select class="form-control" name="questions[${questionId}][answer]" id="mc_answer_${questionId}">
                            <option value="">Select the correct answer</option>
                        </select>
                        <small class="form-text text-muted">Choose which option is the correct answer.</small>
                    </div>
                </div>
                <div id="true_false_section_${questionId}" style="display: none;">
                    <div class="form-group">
                        <label>Correct Answer <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="questions[${questionId}][answer]" id="true_option_${questionId}" value="True">
                            <label class="form-check-label" for="true_option_${questionId}">
                                <i class="fas fa-check text-success"></i> True
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="questions[${questionId}][answer]" id="false_option_${questionId}" value="False">
                            <label class="form-check-label" for="false_option_${questionId}">
                                <i class="fas fa-times text-danger"></i> False
                            </label>
                        </div>
                        <small class="form-text text-muted">Select whether the statement is true or false.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label for="points_${questionId}">Points</label>
                    <input type="number" class="form-control" name="questions[${questionId}][points]" id="points_${questionId}" value="1" min="1" max="10">
                    <small class="form-text text-muted">Points awarded for correct answer (1-10).</small>
                </div>
            </div>
        </div>`;
            return questionBlock;
        }

        function removeQuestion(button) {
            const questionBlock = button.closest('.question-block');
            questionBlock.remove();
            updateRemoveButtons();
        }

        function handleTypeChange(questionId) {
            const typeSelect = document.getElementById(`quiz_type_${questionId}`);
            const mcSection = document.getElementById(`multiple_choice_section_${questionId}`);
            const tfSection = document.getElementById(`true_false_section_${questionId}`);
            if (typeSelect.value === 'multiple_choice') {
                mcSection.style.display = 'block';
                tfSection.style.display = 'none';
            } else if (typeSelect.value === 'true_false') {
                mcSection.style.display = 'none';
                tfSection.style.display = 'block';
            } else {
                mcSection.style.display = 'none';
                tfSection.style.display = 'none';
            }
        }

        function addOption(questionId) {
            const container = document.getElementById(`options_container_${questionId}`);
            const currentOptions = container.querySelectorAll('.option-input').length;
            if (currentOptions >= maxOptions) {
                toastr.warning('Maximum number of options reached');
                return;
            }
            const label = optionLabels[currentOptions];
            const optionDiv = document.createElement('div');
            optionDiv.className = 'input-group mb-2 option-input';
            optionDiv.innerHTML = `
            <div class="input-group-prepend">
                <span class="input-group-text">${label})</span>
            </div>
            <input type="text" class="form-control" name="questions[${questionId}][options][]" placeholder="Enter option ${label}" oninput="updateAnswerOptions(${questionId})">
            <div class="input-group-append">
                <button type="button" class="btn btn-danger" onclick="removeOption(this, ${questionId})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>`;
            container.appendChild(optionDiv);
            updateAnswerOptions(questionId);
        }

        function removeOption(button, questionId) {
            const optionDiv = button.closest('.option-input');
            optionDiv.remove();
            updateAnswerOptions(questionId);
        }

        function updateAnswerOptions(questionId) {
            const container = document.getElementById(`options_container_${questionId}`);
            const select = document.getElementById(`mc_answer_${questionId}`);
            const options = container.querySelectorAll('input[type="text"]');
            select.innerHTML = '<option value="">Select the correct answer</option>';
            options.forEach((opt, index) => {
                const label = optionLabels[index];
                const optionEl = document.createElement('option');
                optionEl.value = label;
                optionEl.textContent = label;
                if (opt.value === '' && select.value === label) {
                    select.value = '';
                }
                select.appendChild(optionEl);
            });
            container.querySelectorAll('.btn-danger').forEach((btn, index) => {
                btn.disabled = index < 2;
            });
        }

        function updateRemoveButtons() {
            const blocks = document.querySelectorAll('#questionsContainer .question-block');
            blocks.forEach((block, index) => {
                const btn = block.querySelector('.card-tools .btn-danger');
                if (btn) {
                    btn.style.display = blocks.length > 1 ? 'inline-block' : 'none';
                }
                const title = block.querySelector('.card-title');
                if (title) {
                    title.textContent = `Question #${index + 1}`;
                }
                block.setAttribute('data-question-id', index + 1);
                const textArea = block.querySelector('textarea');
                if (textArea) {
                    textArea.name = `questions[${index + 1}][question]`;
                    textArea.id = `question_${index + 1}`;
                }
                const typeSelect = block.querySelector('select[name^="questions"][name$="[type]"]');
                if (typeSelect) {
                    typeSelect.name = `questions[${index + 1}][type]`;
                    typeSelect.id = `quiz_type_${index + 1}`;
                    typeSelect.setAttribute('onchange', `handleTypeChange(${index + 1})`);
                }
                const mcSection = block.querySelector('[id^="multiple_choice_section_"]');
                if (mcSection) mcSection.id = `multiple_choice_section_${index + 1}`;
                const tfSection = block.querySelector('[id^="true_false_section_"]');
                if (tfSection) tfSection.id = `true_false_section_${index + 1}`;
                const optionContainer = block.querySelector('[id^="options_container_"]');
                if (optionContainer) optionContainer.id = `options_container_${index + 1}`;
                const answerSelect = block.querySelector('[id^="mc_answer_"]');
                if (answerSelect) answerSelect.id = `mc_answer_${index + 1}`;
                const trueRadio = block.querySelector('[id^="true_option_"]');
                if (trueRadio) {
                    trueRadio.id = `true_option_${index + 1}`;
                    trueRadio.name = `questions[${index + 1}][answer]`;
                }
                const falseRadio = block.querySelector('[id^="false_option_"]');
                if (falseRadio) {
                    falseRadio.id = `false_option_${index + 1}`;
                    falseRadio.name = `questions[${index + 1}][answer]`;
                }
                const pointsInput = block.querySelector('input[name$="[points]"]');
                if (pointsInput) {
                    pointsInput.name = `questions[${index + 1}][points]`;
                    pointsInput.id = `points_${index + 1}`;
                }
                const idInput = block.querySelector('input[name$="[id]"]');
                if (idInput) {
                    idInput.name = `questions[${index + 1}][id]`;
                }
            });
        }
    </script>
@endsection
