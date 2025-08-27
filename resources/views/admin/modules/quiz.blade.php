@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create Quiz for {{ $module->title }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" onclick="addNewQuestion()">
                                <i class="fas fa-plus"></i> Add Another Question
                            </button>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('modules.quiz.store', $module) }}" id="quizForm">
                        @csrf
                        <div class="card-body">
                            <div id="questionsContainer">
                                <div class="question-block" data-question-id="1">
                                    <div class="card card-outline card-info mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title">Question #1</h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeQuestion(this)" style="display: none;">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Question Field -->
                                            <div class="form-group">
                                                <label for="question_1">Question <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="questions[1][question]" id="question_1" rows="3"
                                                    placeholder="Enter your quiz question..." required></textarea>
                                                <small class="form-text text-muted">Write a clear and concise question for
                                                    your quiz.</small>
                                            </div>

                                            <!-- Quiz Type Selection -->
                                            <div class="form-group">
                                                <label for="quiz_type_1">Quiz Type <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="questions[1][type]" id="quiz_type_1"
                                                    onchange="handleTypeChange(1)" required>
                                                    <option value="">Select Quiz Type</option>
                                                    <option value="multiple_choice">Multiple Choice</option>
                                                    <option value="true_false">True / False</option>
                                                </select>
                                                <small class="form-text text-muted">Choose the type of quiz
                                                    question.</small>
                                            </div>

                                            <!-- Multiple Choice Options -->
                                            <div id="multiple_choice_section_1" style="display: none;">
                                                <div class="form-group">
                                                    <label>Answer Options <span class="text-danger">*</span></label>
                                                    <small class="form-text text-muted mb-2">Add multiple choice options. At
                                                        least 2 options are required.</small>

                                                    <div id="options_container_1">
                                                        <div class="input-group mb-2 option-input">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">A)</span>
                                                            </div>
                                                            <input type="text" class="form-control"
                                                                name="questions[1][options][]" placeholder="Enter option A"
                                                                oninput="updateAnswerOptions(1)">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-danger"
                                                                    onclick="removeOption(this, 1)" disabled>
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="input-group mb-2 option-input">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">B)</span>
                                                            </div>
                                                            <input type="text" class="form-control"
                                                                name="questions[1][options][]" placeholder="Enter option B"
                                                                oninput="updateAnswerOptions(1)">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-danger"
                                                                    onclick="removeOption(this, 1)" disabled>
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        onclick="addOption(1)">
                                                        <i class="fas fa-plus"></i> Add Option
                                                    </button>
                                                </div>

                                                <!-- Multiple Choice Answer -->
                                                <div class="form-group">
                                                    <label for="mc_answer_1">Correct Answer <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="questions[1][answer]"
                                                        id="mc_answer_1">
                                                        <option value="">Select the correct answer</option>
                                                    </select>
                                                    <small class="form-text text-muted">Choose which option is the correct
                                                        answer.</small>
                                                </div>
                                            </div>

                                            <!-- True/False Options -->
                                            <div id="true_false_section_1" style="display: none;">
                                                <div class="form-group">
                                                    <label>Correct Answer <span class="text-danger">*</span></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="questions[1][answer]" id="true_option_1" value="True">
                                                        <label class="form-check-label" for="true_option_1">
                                                            <i class="fas fa-check text-success"></i> True
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="questions[1][answer]" id="false_option_1"
                                                            value="False">
                                                        <label class="form-check-label" for="false_option_1">
                                                            <i class="fas fa-times text-danger"></i> False
                                                        </label>
                                                    </div>
                                                    <small class="form-text text-muted">Select whether the statement is
                                                        true or false.</small>
                                                </div>
                                            </div>

                                            <!-- Points Field -->
                                            <div class="form-group">
                                                <label for="points_1">Points</label>
                                                <input type="number" class="form-control" name="questions[1][points]"
                                                    id="points_1" value="1" min="1" max="10">
                                                <small class="form-text text-muted">Points awarded for correct answer
                                                    (1-10).</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save All Quiz Questions
                            </button>
                            {{-- <a href="{{ route('modules.show', $module) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Module
                            </a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let questionCounter = 1; // Track number of questions
        const maxOptions = 6; // Maximum number of options per question
        const optionLabels = ['A', 'B', 'C', 'D', 'E', 'F'];

        function addNewQuestion() {
            questionCounter++;
            const container = document.getElementById('questionsContainer');
            const newQuestion = createQuestionBlock(questionCounter);
            container.appendChild(newQuestion);

            // Show remove button for all questions except the first one
            updateRemoveButtons();

            // Scroll to the new question
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
                <!-- Question Field -->
                <div class="form-group">
                    <label for="question_${questionId}">Question <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="questions[${questionId}][question]" id="question_${questionId}" rows="3"
                              placeholder="Enter your quiz question..." required></textarea>
                    <small class="form-text text-muted">Write a clear and concise question for your quiz.</small>
                </div>

                <!-- Quiz Type Selection -->
                <div class="form-group">
                    <label for="quiz_type_${questionId}">Quiz Type <span class="text-danger">*</span></label>
                    <select class="form-control" name="questions[${questionId}][type]" id="quiz_type_${questionId}" onchange="handleTypeChange(${questionId})" required>
                        <option value="">Select Quiz Type</option>
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="true_false">True / False</option>
                    </select>
                    <small class="form-text text-muted">Choose the type of quiz question.</small>
                </div>

                <!-- Multiple Choice Options -->
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

                    <!-- Multiple Choice Answer -->
                    <div class="form-group">
                        <label for="mc_answer_${questionId}">Correct Answer <span class="text-danger">*</span></label>
                        <select class="form-control" name="questions[${questionId}][answer]" id="mc_answer_${questionId}">
                            <option value="">Select the correct answer</option>
                        </select>
                        <small class="form-text text-muted">Choose which option is the correct answer.</small>
                    </div>
                </div>

                <!-- True/False Options -->
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

                <!-- Points Field -->
                <div class="form-group">
                    <label for="points_${questionId}">Points</label>
                    <input type="number" class="form-control" name="questions[${questionId}][points]" id="points_${questionId}"
                           value="1" min="1" max="10">
                    <small class="form-text text-muted">Points awarded for correct answer (1-10).</small>
                </div>
            </div>
        </div>
    `;

            return questionBlock;
        }

        function removeQuestion(button) {
            const questionBlock = button.closest('.question-block');
            const questionId = questionBlock.getAttribute('data-question-id');

            if (confirm(`Are you sure you want to remove Question #${questionId}?`)) {
                questionBlock.remove();
                renumberQuestions();
                updateRemoveButtons();
                toastr.success('Question removed successfully!');
            }
        }

        function renumberQuestions() {
            const questionBlocks = document.querySelectorAll('.question-block');
            questionCounter = questionBlocks.length;

            questionBlocks.forEach((block, index) => {
                const newId = index + 1;
                block.setAttribute('data-question-id', newId);

                // Update all IDs and names in the block
                const cardTitle = block.querySelector('.card-title');
                cardTitle.textContent = `Question #${newId}`;

                // Update form field names and IDs
                const questionTextarea = block.querySelector('textarea');
                questionTextarea.name = `questions[${newId}][question]`;
                questionTextarea.id = `question_${newId}`;

                const typeSelect = block.querySelector('select[name*="[type]"]');
                typeSelect.name = `questions[${newId}][type]`;
                typeSelect.id = `quiz_type_${newId}`;
                typeSelect.onchange = () => handleTypeChange(newId);

                const mcSection = block.querySelector(
                    `#multiple_choice_section_${block.getAttribute('data-question-id')}`);
                if (mcSection) {
                    mcSection.id = `multiple_choice_section_${newId}`;
                    const optionsContainer = mcSection.querySelector(
                        `#options_container_${block.getAttribute('data-question-id')}`);
                    if (optionsContainer) {
                        optionsContainer.id = `options_container_${newId}`;
                        // Update option input names
                        const optionInputs = optionsContainer.querySelectorAll('input[name*="[options]"]');
                        optionInputs.forEach(input => {
                            input.name = `questions[${newId}][options][]`;
                            input.oninput = () => updateAnswerOptions(newId);
                        });
                        // Update remove option buttons
                        const removeButtons = optionsContainer.querySelectorAll('.btn-danger');
                        removeButtons.forEach(btn => {
                            btn.onclick = () => removeOption(btn, newId);
                        });
                        // Update add option button
                        const addButton = mcSection.querySelector('.btn-secondary');
                        addButton.onclick = () => addOption(newId);
                    }
                    const mcAnswer = mcSection.querySelector(
                    `#mc_answer_${block.getAttribute('data-question-id')}`);
                    if (mcAnswer) {
                        mcAnswer.name = `questions[${newId}][answer]`;
                        mcAnswer.id = `mc_answer_${newId}`;
                    }
                }

                const tfSection = block.querySelector(
                    `#true_false_section_${block.getAttribute('data-question-id')}`);
                if (tfSection) {
                    tfSection.id = `true_false_section_${newId}`;
                    const radioButtons = tfSection.querySelectorAll('input[type="radio"]');
                    radioButtons.forEach((radio, radioIndex) => {
                        radio.name = `questions[${newId}][answer]`;
                        radio.id = radioIndex === 0 ? `true_option_${newId}` : `false_option_${newId}`;
                    });
                    const labels = tfSection.querySelectorAll('label[for*="option_"]');
                    labels.forEach((label, labelIndex) => {
                        label.setAttribute('for', labelIndex === 0 ? `true_option_${newId}` :
                            `false_option_${newId}`);
                    });
                }

                const pointsInput = block.querySelector('input[name*="[points]"]');
                pointsInput.name = `questions[${newId}][points]`;
                pointsInput.id = `points_${newId}`;
            });
        }

        function updateRemoveButtons() {
            const questionBlocks = document.querySelectorAll('.question-block');
            const removeButtons = document.querySelectorAll('.question-block .btn-danger');

            removeButtons.forEach(button => {
                button.style.display = questionBlocks.length > 1 ? 'inline-block' : 'none';
            });
        }

        function handleTypeChange(questionId) {
            const quizType = document.getElementById(`quiz_type_${questionId}`).value;
            const mcSection = document.getElementById(`multiple_choice_section_${questionId}`);
            const tfSection = document.getElementById(`true_false_section_${questionId}`);

            // Hide all sections first
            mcSection.style.display = 'none';
            tfSection.style.display = 'none';

            // Clear previous answers
            const mcAnswer = document.getElementById(`mc_answer_${questionId}`);
            if (mcAnswer) {
                mcAnswer.innerHTML = '<option value="">Select the correct answer</option>';
            }

            // Show relevant section
            if (quizType === 'multiple_choice') {
                mcSection.style.display = 'block';
                updateAnswerOptions(questionId);
                updateOptionRemoveButtons(questionId);
            } else if (quizType === 'true_false') {
                tfSection.style.display = 'block';
                // Clear radio buttons
                document.getElementById(`true_option_${questionId}`).checked = false;
                document.getElementById(`false_option_${questionId}`).checked = false;
            }
        }

        function addOption(questionId) {
            const container = document.getElementById(`options_container_${questionId}`);
            const optionInputs = container.querySelectorAll('.option-input');

            if (optionInputs.length >= maxOptions) {
                toastr.warning(`Maximum ${maxOptions} options allowed per question`);
                return;
            }

            const newOption = document.createElement('div');
            newOption.className = 'input-group mb-2 option-input';

            newOption.innerHTML = `
        <div class="input-group-prepend">
            <span class="input-group-text">${optionLabels[optionInputs.length]})</span>
        </div>
        <input type="text" class="form-control" name="questions[${questionId}][options][]" placeholder="Enter option ${optionLabels[optionInputs.length]}" oninput="updateAnswerOptions(${questionId})">
        <div class="input-group-append">
            <button type="button" class="btn btn-danger" onclick="removeOption(this, ${questionId})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

            container.appendChild(newOption);
            updateAnswerOptions(questionId);
            updateOptionRemoveButtons(questionId);

            // Focus on the new input
            const newInput = newOption.querySelector('input');
            newInput.focus();
        }

        function removeOption(button, questionId) {
            const container = document.getElementById(`options_container_${questionId}`);
            const optionInputs = container.querySelectorAll('.option-input');

            if (optionInputs.length <= 2) {
                toastr.error('At least 2 options are required');
                return;
            }

            button.closest('.option-input').remove();
            relabelOptions(questionId);
            updateAnswerOptions(questionId);
            updateOptionRemoveButtons(questionId);
        }

        function relabelOptions(questionId) {
            const container = document.getElementById(`options_container_${questionId}`);
            const optionInputs = container.querySelectorAll('.option-input');

            optionInputs.forEach((input, index) => {
                const label = input.querySelector('.input-group-text');
                const textInput = input.querySelector('input');
                label.textContent = `${optionLabels[index]})`;
                textInput.placeholder = `Enter option ${optionLabels[index]}`;
            });
        }

        function updateAnswerOptions(questionId) {
            const answerSelect = document.getElementById(`mc_answer_${questionId}`);
            if (!answerSelect) return;

            const currentValue = answerSelect.value;

            // Clear existing options
            answerSelect.innerHTML = '<option value="">Select the correct answer</option>';

            // Get all option inputs for this question
            const container = document.getElementById(`options_container_${questionId}`);
            const optionInputs = container.querySelectorAll('.option-input input');

            optionInputs.forEach((input, index) => {
                const option = document.createElement('option');
                option.value = optionLabels[index];
                option.textContent = `${optionLabels[index]}) ${input.value || 'Option ' + optionLabels[index]}`;
                answerSelect.appendChild(option);
            });

            // Restore previous selection if valid
            if (currentValue && answerSelect.querySelector(`option[value="${currentValue}"]`)) {
                answerSelect.value = currentValue;
            }
        }

        function updateOptionRemoveButtons(questionId) {
            const container = document.getElementById(`options_container_${questionId}`);
            const optionInputs = container.querySelectorAll('.option-input');
            const removeButtons = container.querySelectorAll('.option-input .btn-danger');

            removeButtons.forEach(button => {
                button.disabled = optionInputs.length <= 2;
            });
        }

        // Add event listeners when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add input event listeners to existing options for first question
            const existingInputs = document.querySelectorAll('#options_container_1 .option-input input');
            existingInputs.forEach(input => {
                input.addEventListener('input', () => updateAnswerOptions(1));
            });

            // Form validation
            document.getElementById('quizForm').addEventListener('submit', function(e) {
                const questionBlocks = document.querySelectorAll('.question-block');
                let isValid = true;

                questionBlocks.forEach((block, index) => {
                    const questionId = block.getAttribute('data-question-id');
                    const question = block.querySelector('textarea').value.trim();
                    const quizType = block.querySelector('select[name*="[type]"]').value;

                    if (!question) {
                        toastr.error(`Please enter a question for Question #${questionId}`);
                        isValid = false;
                        return;
                    }

                    if (!quizType) {
                        toastr.error(`Please select a quiz type for Question #${questionId}`);
                        isValid = false;
                        return;
                    }

                    if (quizType === 'multiple_choice') {
                        const container = document.getElementById(
                        `options_container_${questionId}`);
                        const options = container.querySelectorAll('.option-input input');
                        let filledOptions = 0;

                        options.forEach(input => {
                            if (input.value.trim()) filledOptions++;
                        });

                        if (filledOptions < 2) {
                            toastr.error(
                                `Please provide at least 2 options for Question #${questionId}`);
                            isValid = false;
                            return;
                        }

                        const answer = document.getElementById(`mc_answer_${questionId}`).value;
                        if (!answer) {
                            toastr.error(
                                `Please select the correct answer for Question #${questionId}`);
                            isValid = false;
                            return;
                        }
                    }

                    if (quizType === 'true_false') {
                        const trueChecked = document.getElementById(`true_option_${questionId}`)
                            .checked;
                        const falseChecked = document.getElementById(`false_option_${questionId}`)
                            .checked;

                        if (!trueChecked && !falseChecked) {
                            toastr.error(
                                `Please select whether the answer is True or False for Question #${questionId}`
                                );
                            isValid = false;
                            return;
                        }
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    return false;
                }

                // Show success message
                toastr.success(`Submitting ${questionBlocks.length} quiz question(s)...`);
            });
        });
    </script>
@endsection
