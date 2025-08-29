@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <!-- Course Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 course-header-card fade-in-up">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="course-title-section">
                                <h1 class="display-5 fw-bold text-primary mb-2">
                                    <i class="fas fa-graduation-cap me-2"></i>{{ $course->title }}
                                </h1>
                                <p class="text-muted course-subtitle">Course Overview & Management</p>
                            </div>
                            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to Courses
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Details Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 course-details-card fade-in-up">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Course Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <div class="info-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-folder"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">Category</h6>
                                    <p class="fw-bold mb-0">{{ $course->category->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <div class="info-icon bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-signal"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">Level</h6>
                                    <p class="fw-bold mb-0">{{ $course->level->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <div class="info-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h6 class="text-muted mb-1">Status</h6>
                                    <span
                                        class="badge bg-{{ $course->status === 'active' ? 'success' : ($course->status === 'draft' ? 'warning' : 'secondary') }} fs-6">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </div>
                            </div>
                            @if ($course->is_premium)
                                <div class="col-md-3 mb-3">
                                    <div class="info-item">
                                        <div class="info-icon bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <h6 class="text-muted mb-1">Price</h6>
                                        <p class="fw-bold text-success mb-0">${{ $course->price }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($course->description)
                            <hr class="my-4">
                            <div class="description-section">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-align-left me-2"></i>Description
                                </h6>
                                <p class="lead text-dark">{{ $course->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 modules-section-card fade-in-up">
                    <div class="card-header bg-gradient-success text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-puzzle-piece me-2"></i>Course Modules
                                <span class="badge bg-light text-dark ms-2">{{ count($course->modules) }}</span>
                            </h5>
                            <a href="{{ route('modules.create', $course) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-2"></i>Add Module
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <ul id="modules-list" class="list-unstyled modules-list">
                            @forelse ($course->modules as $module)
                                <li class="module-item mb-3" data-id="{{ $module->id }}">
                                    <div class="card border-0 shadow-sm module-card" style="width: 99%;">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <span class="handle me-3 text-muted cursor-move" title="Drag to reorder">
                                                    <i class="fas fa-grip-vertical fs-5 mr-2"></i>
                                                </span>
                                                <div class="module-info flex-grow-1">
                                                    <h6 class="mb-1 fw-bold ">{{ $module->title }}</h6>
                                                    @if ($module->description)
                                                        <p class="text-muted small mb-0">
                                                            {{ Str::limit($module->description, 80) }}</p>
                                                    @endif
                                                </div>
                                                <div class="module-actions">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('modules.show', $module) }}"
                                                            class="btn btn-outline-info btn-sm" title="View Module">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('modules.edit', $module) }}"
                                                            class="btn btn-outline-warning btn-sm" title="Edit Module">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('modules.destroy', $module) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                title="Delete Module"
                                                                onclick="return confirm('Are you sure you want to delete this module?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No modules yet</h5>
                                        <p class="text-muted">Start building your course by adding the first module.</p>
                                        <a href="{{ route('modules.create', $course) }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create First Module
                                        </a>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/jquery-ui.min.css"
        integrity="sha512-TFee0335YRJoyiqz8hA8KV3P0tXa5CpRBSoM0Wnkn7JoJx1kaq1yXL/rb8YFpWXkMOjRcv5txv+C6UluttluCQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/jquery-ui.min.js"
        integrity="sha512-MSOo1aY+3pXCOCdGAYoBZ6YGI0aragoQsg1mKKBHXCYPIWxamwOE7Drh+N5CPgGI5SA9IEKJiPjdfqWFWmZtRA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function() {
            $('#modules-list').sortable({
                handle: '.handle',
                update: function(e, ui) {
                    let order = $(this).children().map(function() {
                        return $(this).data('id');
                    }).get();
                    $.post('{{ route('modules.reorder', $course) }}', {
                        _token: '{{ csrf_token() }}',
                        order: order
                    });
                }
            });
        });
    </script>
@endsection
