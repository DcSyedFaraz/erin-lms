@extends('admin.layout.app')
@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container-fluid">
    <!-- Module Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 module-header-card fade-in-up">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="module-title-section">
                            <div class="d-flex align-items-center mb-2">
                                <a href="{{ route('courses.show', $module->course) }}" class="text-muted text-decoration-none me-3" title="Back to Course">
                                    <i class="fas fa-graduation-cap"></i> {{ $module->course->title }}
                                </a>
                                <i class="fas fa-chevron-right text-muted me-3"></i>
                                <span class="text-muted">Module</span>
                            </div>
                            <h1 class="display-6 fw-bold text-primary mb-2">
                                <i class="fas fa-puzzle-piece me-2"></i>{{ $module->title }}
                            </h1>
                            @if($module->description)
                                <p class="text-muted module-subtitle mb-0">{{ $module->description }}</p>
                            @endif
                        </div>
                        <div class="module-actions">
                            <div class="btn-group" role="group">
                                <a href="{{ route('modules.edit', $module) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Edit Module
                                </a>
                                <a href="{{ route('courses.show', $module->course) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Course
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Module Contents Section -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 module-contents-card fade-in-up">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Module Contents
                        <span class="badge bg-light text-dark ms-2">{{ count($module->contents) }}</span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    @forelse($module->contents as $content)
                        <div class="content-item mb-3 p-3 border rounded bg-light">
                            @if($content->type === 'text')
                                <div class="d-flex align-items-start">
                                    <div class="content-icon me-3">
                                        <i class="fas fa-align-left text-primary fa-lg"></i>
                                    </div>
                                    <div class="content-body flex-grow-1">
                                        <h6 class="text-muted mb-2">Text Content</h6>
                                        <div class="mb-0 text-dark">{!! $content->text !!}</div>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="content-icon me-3">
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
                                        <div class="content-body">
                                            <h6 class="mb-1">{{ ucfirst($content->type) }} File</h6>
                                            <p class="text-muted small mb-0">{{ basename($content->path) }}</p>
                                        </div>
                                    </div>
                                    <div class="content-actions">
                                        <a href="{{ Storage::url($content->path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-external-link-alt me-1"></i>Open
                                        </a>
                                        @if($content->type === 'image')
                                            <button type="button" class="btn btn-outline-info btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#imageModal{{ $loop->index }}">
                                                <i class="fas fa-eye me-1"></i>Preview
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                @if($content->type === 'image')
                                    <!-- Image Preview Modal (Bootstrap 5 compatible) -->
                                    <div class="modal fade" id="imageModal{{ $loop->index }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $loop->index }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="imageModalLabel{{ $loop->index }}">Image Preview</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ Storage::url($content->path) }}" class="img-fluid rounded" alt="Content Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No content available</h5>
                                <p class="text-muted">This module doesn't have any content yet.</p>
                                <a href="{{ route('modules.edit', $module) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add Content
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Module Quizzes & Actions Sidebar -->
        <div class="col-lg-4">
            <!-- Quizzes Section -->
            <div class="card shadow-sm border-0 module-quizzes-card mb-4 fade-in-up">
                <div class="card-header bg-gradient-warning text-white py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>Module Quizzes
                        <span class="badge bg-light text-dark ms-2">{{ count($module->quizzes) }}</span>
                    </h6>
                </div>
                <div class="card-body p-3">
                    @forelse($module->quizzes as $quiz)
                        <div class="quiz-item mb-3 p-3 border rounded bg-light">
                            <div class="d-flex align-items-start">
                                <div class="quiz-icon me-2">
                                    <i class="fas fa-question text-warning"></i>
                                </div>
                                <div class="quiz-body flex-grow-1">
                                    <p class="mb-0 text-dark">{{ $quiz->question }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">No quizzes available</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Module Statistics Card -->
            <div class="card shadow-sm border-0 module-stats-card mb-4 fade-in-up">
                <div class="card-header bg-gradient-secondary text-white py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Module Statistics
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h4 class="text-primary mb-1">{{ count($module->contents) }}</h4>
                                <small class="text-muted">Contents</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stat-item">
                                <h4 class="text-warning mb-1">{{ count($module->quizzes) }}</h4>
                                <small class="text-muted">Quizzes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card shadow-sm border-danger fade-in-up">
                <div class="card-header bg-danger text-white py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                    </h6>
                </div>
                <div class="card-body p-3">
                    <p class="text-muted small mb-3">Permanently delete this module and all its contents. This action cannot be undone.</p>
                    <form action="{{ route('modules.destroy', $module) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this module? This action cannot be undone.')">
                            <i class="fas fa-trash me-2"></i>Delete Module
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
