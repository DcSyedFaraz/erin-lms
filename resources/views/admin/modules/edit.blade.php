@extends('admin.layout.app')
@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <div class="container">
        <!-- Module Edit Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 module-edit-header-card fade-in-up">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="module-edit-title-section">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="{{ route('courses.show', $module->course) }}"
                                        class="text-muted text-decoration-none me-3" title="Back to Course">
                                        <i class="fas fa-graduation-cap"></i> {{ $module->course->title }}
                                    </a>
                                    <i class="fas fa-chevron-right text-muted me-3"></i>
                                    <a href="{{ route('modules.show', $module) }}"
                                        class="text-muted text-decoration-none me-3">{{ $module->title }}</a>
                                    <i class="fas fa-chevron-right text-muted me-3"></i>
                                    <span class="text-muted">Edit</span>
                                </div>
                                <h1 class="display-6 fw-bold text-primary mb-0">
                                    <i class="fas fa-edit me-2"></i>Edit Module
                                </h1>
                            </div>
                            <div class="module-edit-actions">
                                <a href="{{ route('modules.show', $module) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('modules.update', $module) }}" enctype="multipart/form-data"
            class="module-edit-form">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Main Form Content -->
                <div class="col-lg-8">
                    <!-- Basic Information Card -->
                    <div class="card shadow-sm border-0 mb-4 fade-in-up">
                        <div class="card-header bg-gradient-primary text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">
                                    <i class="fas fa-heading me-2 text-primary"></i>Module Title
                                </label>
                                <input type="text"
                                    class="form-control form-control-lg @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title', $module->title) }}" required
                                    placeholder="Enter module title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">
                                    <i class="fas fa-align-left me-2 text-primary"></i>Description
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4" placeholder="Enter module description">{{ old('description', $module->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Text Contents Card -->
                    <div class="card shadow-sm border-0 mb-4 fade-in-up">
                        <div class="card-header bg-gradient-info text-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-font me-2"></i>Text Contents
                                </h5>
                                <button type="button" class="btn btn-light btn-sm" onclick="addText()">
                                    <i class="fas fa-plus me-2"></i>Add Text Field
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div id="texts" class="text-contents-container">
                                @forelse($module->contents->where('type', 'text') as $content)
                                    <div class="text-content-item mb-3">
                                        <div class="input-group mb-2">
                                            <span class="input-group-text bg-info text-white">
                                                <i class="fas fa-align-left"></i>
                                            </span>
                                            <button type="button" class="btn btn-outline-danger" onclick="removeTextContent(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <textarea class="form-control summernote" name="text_contents[]" placeholder="Enter text content">{!! old('text_contents.' . $loop->index, $content->text) !!}</textarea>
                                    </div>
                                @empty
                                    <div class="text-content-item mb-3">
                                        <div class="input-group mb-2">
                                            <span class="input-group-text bg-info text-white">
                                                <i class="fas fa-align-left"></i>
                                            </span>
                                            <button type="button" class="btn btn-outline-danger" onclick="removeTextContent(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <textarea class="form-control summernote" name="text_contents[]" placeholder="Enter text content"></textarea>
                                    </div>
                                @endforelse
                            </div>

                            @if ($module->contents->where('type', 'text')->isEmpty())
                                <div class="text-center py-3 text-muted empty-text-state">
                                    <i class="fas fa-font fa-2x mb-2"></i>
                                    <p class="mb-0">No text content yet. Click "Add Text Field" to start adding content.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- File Upload Card -->
                    <div class="card shadow-sm border-0 mb-4 fade-in-up">
                        <div class="card-header bg-gradient-success text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-cloud-upload-alt me-2"></i>Upload New Files
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="pdfs" class="form-label fw-bold">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>PDF Files
                                    </label>
                                    <input type="file" class="form-control" id="pdfs" name="pdfs[]"
                                        accept=".pdf" multiple>
                                    <small class="form-text text-muted">Select one or more PDF files</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="images" class="form-label fw-bold">
                                        <i class="fas fa-image text-success me-2"></i>Images
                                    </label>
                                    <input type="file" class="form-control" id="images" name="images[]"
                                        accept="image/*" multiple>
                                    <small class="form-text text-muted">Select one or more image files</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="videos" class="form-label fw-bold">
                                        <i class="fas fa-video text-warning me-2"></i>Videos
                                    </label>
                                    <input type="file" class="form-control" id="videos" name="videos[]"
                                        accept="video/*" multiple>
                                    <small class="form-text text-muted">Select one or more video files</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Existing Files Card -->
                    @if ($module->contents->where('type', '!=', 'text')->isNotEmpty())
                        <div class="card shadow-sm border-0 mb-4 fade-in-up">
                            <div class="card-header bg-gradient-warning text-white py-3">
                                <h6 class="mb-0">
                                    <i class="fas fa-folder-open me-2"></i>Existing Files
                                    <span
                                        class="badge bg-light text-dark ms-2">{{ $module->contents->where('type', '!=', 'text')->count() }}</span>
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                @foreach ($module->contents->where('type', '!=', 'text') as $content)
                                    <div class="existing-file-item mb-3 p-3 border rounded bg-light">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="file-icon me-3">
                                                    @switch($content->type)
                                                        @case('pdf')
                                                            <i class="fas fa-file-pdf text-danger fa-lg"></i>
                                                        @break

                                                        @case('image')
                                                            <i class="fas fa-image text-success fa-lg"></i>
                                                        @break

                                                        @case('video')
                                                            <i class="fas fa-video text-warning fa-lg"></i>
                                                        @break

                                                        @default
                                                            <i class="fas fa-file text-secondary fa-lg"></i>
                                                    @endswitch
                                                </div>
                                                <div class="file-info">
                                                    <h6 class="mb-1">{{ ucfirst($content->type) }} File</h6>
                                                    <p class="text-muted small mb-0">{{ basename($content->path) }}</p>
                                                </div>
                                            </div>
                                            <div class="file-actions">
                                                <a href="{{ Storage::url($content->path) }}" target="_blank"
                                                    class="btn btn-outline-primary btn-sm me-1" title="View File">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remove_contents[]"
                                                    value="{{ $content->id }}" id="remove_{{ $content->id }}">
                                                <label class="form-check-label text-danger"
                                                    for="remove_{{ $content->id }}">
                                                    <i class="fas fa-trash me-1"></i>Remove this file
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons Card -->
                    <div class="card shadow-sm border-0 mb-4 fade-in-up">
                        <div class="card-header bg-gradient-secondary text-white py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>Actions
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i>Update Module
                                </button>
                                <a href="{{ route('modules.show', $module) }}" class="btn btn-outline-info">
                                    <i class="fas fa-eye me-2"></i>Preview Module
                                </a>
                                <a href="{{ route('modules.quiz.edit', $module) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-question-circle me-2"></i>Edit Quizzes
                                </a>
                                <hr class="my-3">
                                <a href="{{ route('courses.show', $module->course) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Course
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="card shadow-sm border-info">
                        <div class="card-header bg-info text-white py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-question-circle me-2"></i>Help & Tips
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Use descriptive
                                    titles for better organization</li>
                                <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Text content supports
                                    multiple entries</li>
                                <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>You can upload
                                    multiple files at once</li>
                                <li class="mb-0"><i class="fas fa-lightbulb text-warning me-2"></i>Check "Remove" to
                                    delete existing files</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        // Initialize Summernote editors on load
        $(function() {
            $('.summernote').summernote({
                placeholder: 'Enter text content',
                tabsize: 2,
                height: 220,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        });

        function addText() {
            const container = document.getElementById('texts');
            const emptyState = container.querySelector('.empty-text-state');

            // Hide empty state if it exists
            if (emptyState) {
                emptyState.style.display = 'none';
            }

            // Create new text content item
            const textContentItem = document.createElement('div');
            textContentItem.className = 'text-content-item mb-3';
            textContentItem.innerHTML = `
        <div class="input-group mb-2">
            <span class="input-group-text bg-info text-white">
                <i class="fas fa-align-left"></i>
            </span>
            <button type="button" class="btn btn-outline-danger" onclick="removeTextContent(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <textarea class="form-control summernote" name="text_contents[]" placeholder="Enter text content"></textarea>
    `;

            container.appendChild(textContentItem);

            // Initialize Summernote for the new textarea
            const newTextarea = textContentItem.querySelector('textarea.summernote');
            $(newTextarea).summernote({
                placeholder: 'Enter text content',
                tabsize: 2,
                height: 220,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

            // Add fade in animation
            textContentItem.style.opacity = '0';
            textContentItem.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                textContentItem.style.transition = 'all 0.3s ease';
                textContentItem.style.opacity = '1';
                textContentItem.style.transform = 'translateY(0)';
            }, 10);
        }

        function removeTextContent(button) {
            const textContentItem = button.closest('.text-content-item');
            const container = document.getElementById('texts');

            // Add fade out animation
            textContentItem.style.transition = 'all 0.3s ease';
            textContentItem.style.opacity = '0';
            textContentItem.style.transform = 'translateY(-10px)';

            setTimeout(() => {
                textContentItem.remove();

                // Show empty state if no text content items left
                const remainingItems = container.querySelectorAll('.text-content-item');
                const emptyState = container.querySelector('.empty-text-state');

                if (remainingItems.length === 0 && emptyState) {
                    emptyState.style.display = 'block';
                }
            }, 300);
        }

        // File input change handlers for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('input[type="file"]');

            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const files = this.files;
                    const label = this.previousElementSibling;

                    if (files.length > 0) {
                        const fileNames = Array.from(files).map(file => file.name).join(', ');
                        const maxLength = 50;
                        const displayText = fileNames.length > maxLength ?
                            fileNames.substring(0, maxLength) + '...' : fileNames;

                        // Create or update file count indicator
                        let indicator = this.parentNode.querySelector('.file-count-indicator');
                        if (!indicator) {
                            indicator = document.createElement('small');
                            indicator.className = 'file-count-indicator text-success mt-1 d-block';
                            this.parentNode.appendChild(indicator);
                        }

                        indicator.innerHTML =
                            `<i class="fas fa-check-circle me-1"></i>${files.length} file(s) selected: ${displayText}`;
                    }
                });
            });

            // Form validation
            const form = document.querySelector('.module-edit-form');
            form.addEventListener('submit', function(e) {
                const titleInput = document.getElementById('title');

                if (!titleInput.value.trim()) {
                    e.preventDefault();
                    titleInput.focus();
                    titleInput.classList.add('is-invalid');

                    // Show error message
                    let errorDiv = titleInput.nextElementSibling;
                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        titleInput.parentNode.appendChild(errorDiv);
                    }
                    errorDiv.textContent = 'Module title is required.';
                }
            });

            // Remove validation error on input
            document.getElementById('title').addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errorDiv = this.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv.remove();
                }
            });
        });
    </script>
@endsection
