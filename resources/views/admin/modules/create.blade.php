@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <!-- Module Create Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 module-create-header-card fade-in-up">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="module-create-title-section">
                            <div class="d-flex align-items-center mb-2">
                                <a href="{{ route('courses.show', $course) }}" class="text-muted text-decoration-none me-3" title="Back to Course">
                                    <i class="fas fa-graduation-cap"></i> {{ $course->title }}
                                </a>
                                <i class="fas fa-chevron-right text-muted me-3"></i>
                                <span class="text-muted">New Module</span>
                            </div>
                            <h1 class="display-6 fw-bold text-success mb-0">
                                <i class="fas fa-plus-circle me-2"></i>Create New Module
                            </h1>
                        </div>
                        <div class="module-create-actions">
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('modules.store', $course) }}" enctype="multipart/form-data" class="module-create-form">
        @csrf

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
                                <i class="fas fa-heading me-2 text-primary"></i>Module Title <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   required
                                   placeholder="Enter a descriptive module title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Choose a clear, descriptive title that explains what students will learn.</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-2 text-primary"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Provide a detailed description of what this module covers">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Optional: Describe the learning objectives and content overview.</small>
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
                            <div class="text-content-item mb-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-info text-white">
                                        <i class="fas fa-align-left"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           name="text_contents[]"
                                           placeholder="Enter text content (optional)">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeTextContent(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="text-center py-3 text-muted">
                            <small><i class="fas fa-info-circle me-1"></i>Add multiple text content blocks to structure your module content.</small>
                        </div>
                    </div>
                </div>

                <!-- File Upload Card -->
                <div class="card shadow-sm border-0 mb-4 fade-in-up">
                    <div class="card-header bg-gradient-success text-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-cloud-upload-alt me-2"></i>Upload Files
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="pdfs" class="form-label fw-bold">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>PDF Files
                                </label>
                                <input type="file"
                                       class="form-control"
                                       id="pdfs"
                                       name="pdfs[]"
                                       accept=".pdf"
                                       multiple>
                                <small class="form-text text-muted">Upload PDF documents, handouts, or reading materials</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="images" class="form-label fw-bold">
                                    <i class="fas fa-image text-success me-2"></i>Images
                                </label>
                                <input type="file"
                                       class="form-control"
                                       id="images"
                                       name="images[]"
                                       accept="image/*"
                                       multiple>
                                <small class="form-text text-muted">Upload diagrams, charts, or visual content</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="videos" class="form-label fw-bold">
                                    <i class="fas fa-video text-warning me-2"></i>Videos
                                </label>
                                <input type="file"
                                       class="form-control"
                                       id="videos"
                                       name="videos[]"
                                       accept="video/*"
                                       multiple>
                                <small class="form-text text-muted">Upload video lectures or demonstrations</small>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>File Upload Tips:</strong>
                            <ul class="mb-0 mt-2">
                                <li>You can upload multiple files of each type</li>
                                <li>Maximum file size depends on your server configuration</li>
                                <li>Supported formats: PDF, common image formats (JPG, PNG, GIF), common video formats (MP4, AVI, MOV)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Course Information Card -->
                <div class="card shadow-sm border-0 mb-4 fade-in-up">
                    <div class="card-header bg-gradient-secondary text-white py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>Course Information
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="course-icon me-3">
                                <i class="fas fa-book fa-2x text-primary"></i>
                            </div>
                            <div class="course-info">
                                <h6 class="mb-1">{{ $course->title }}</h6>
                                <p class="text-muted small mb-0">{{ $course->modules->count() }} existing modules</p>
                            </div>
                        </div>
                        <p class="text-muted small">This module will be added to the above course.</p>
                    </div>
                </div>

                <!-- Action Buttons Card -->
                <div class="card shadow-sm border-0 mb-4 fade-in-up">
                    <div class="card-header bg-gradient-success text-white py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>Actions
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-plus me-2"></i>Create Module
                            </button>
                            <hr class="my-3">
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Course
                            </a>
                        </div>

                        <div class="mt-3 p-2 bg-light rounded">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                After creating this module, you'll be able to add quizzes and additional content.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Progress Card -->
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-tasks me-2"></i>Module Creation Steps
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="creation-steps">
                            <div class="step active">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="fw-bold">1. Basic Information</span>
                                <small class="d-block text-muted">Title and description</small>
                            </div>
                            <div class="step active">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="fw-bold">2. Add Content</span>
                                <small class="d-block text-muted">Text and file uploads</small>
                            </div>
                            <div class="step">
                                <i class="fas fa-circle text-muted me-2"></i>
                                <span class="text-muted">3. Add Quizzes (Optional)</span>
                                <small class="d-block text-muted">After module creation</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
function addText() {
    const container = document.getElementById('texts');

    // Create new text content item
    const textContentItem = document.createElement('div');
    textContentItem.className = 'text-content-item mb-3';
    textContentItem.innerHTML = `
        <div class="input-group">
            <span class="input-group-text bg-info text-white">
                <i class="fas fa-align-left"></i>
            </span>
            <input type="text"
                   class="form-control"
                   name="text_contents[]"
                   placeholder="Enter text content">
            <button type="button" class="btn btn-outline-danger" onclick="removeTextContent(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    container.appendChild(textContentItem);

    // Focus on the new input
    const newInput = textContentItem.querySelector('input');
    newInput.focus();

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

    // Don't remove if it's the last one
    const remainingItems = container.querySelectorAll('.text-content-item');
    if (remainingItems.length <= 1) {
        // Clear the input instead
        const input = textContentItem.querySelector('input');
        input.value = '';
        input.focus();
        return;
    }

    // Add fade out animation
    textContentItem.style.transition = 'all 0.3s ease';
    textContentItem.style.opacity = '0';
    textContentItem.style.transform = 'translateY(-10px)';

    setTimeout(() => {
        textContentItem.remove();
    }, 300);
}

// File input change handlers for better UX
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');

    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const files = this.files;

            if (files.length > 0) {
                const fileNames = Array.from(files).map(file => file.name).join(', ');
                const maxLength = 40;
                const displayText = fileNames.length > maxLength ?
                    fileNames.substring(0, maxLength) + '...' : fileNames;

                // Create or update file count indicator
                let indicator = this.parentNode.querySelector('.file-count-indicator');
                if (!indicator) {
                    indicator = document.createElement('small');
                    indicator.className = 'file-count-indicator text-success mt-1 d-block';
                    this.parentNode.appendChild(indicator);
                }

                indicator.innerHTML = `<i class="fas fa-check-circle me-1"></i>${files.length} file(s) selected: ${displayText}`;
            }
        });
    });

    // Form validation
    const form = document.querySelector('.module-create-form');
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
@endsection

