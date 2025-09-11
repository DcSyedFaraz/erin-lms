@extends('child.layout.app')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-body bg-gradient-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-1"><i class="fas fa-user-graduate mr-2"></i>{{ $child->name }}'s Learning Dashboard</h2>
                                <p class="mb-0 opacity-75">Welcome back! Continue your learning journey</p>
                            </div>
                            <div>
                                <a href="{{ route('parent.dashboard') }}" class="btn btn-light">
                                    <i class="fas fa-arrow-left mr-1"></i> Back to Parent
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Section -->
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-book-open mr-2 text-primary"></i>My Courses
                            </h3>
                            <span class="badge badge-primary badge-lg">{{ $purchases->count() }} Course{{ $purchases->count() !== 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($purchases->count() > 0)
                            <div class="row">
                                @foreach($purchases as $p)
                                    @php($course = $p->course)
                                    @if ($course)
                                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                                            <div class="card course-card h-100 border-0 shadow-sm">
                                                @if ($course->thumbnail)
                                                    <div class="position-relative">
                                                        <img src="{{ asset("storage/$course->thumbnail") }}"
                                                             class="card-img-top"
                                                             style="height: 160px; object-fit: cover;"
                                                             alt="{{ $course->title }}">
                                                        <div class="position-absolute top-0 right-0 m-2">
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-check mr-1"></i>Enrolled
                                                            </span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient-primary text-white" style="height: 160px;">
                                                        <i class="fas fa-book fa-3x opacity-50"></i>
                                                    </div>
                                                @endif
                                                <div class="card-body d-flex flex-column">
                                                    <h6 class="card-title font-weight-bold mb-2" style="min-height: 48px; line-height: 1.3;">
                                                        {{ $course->title }}
                                                    </h6>
                                                    <p class="card-text text-muted small mb-3 flex-grow-1" style="min-height: 40px;">
                                                        {{ \Illuminate\Support\Str::limit($course->description, 80) }}
                                                    </p>
                                                    <div class="mt-auto">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <small class="text-muted">
                                                                <i class="fas fa-layer-group mr-1"></i>{{ $course->modules->count() }} Modules
                                                            </small>
                                                            <small class="text-success font-weight-bold">
                                                                <i class="fas fa-star mr-1"></i>Premium
                                                            </small>
                                                        </div>
                                                        <a href="{{ route('parent.children.course', [$child, $course]) }}"
                                                           class="btn btn-primary btn-block">
                                                            <i class="fas fa-play mr-1"></i>Start Learning
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-book-open fa-4x text-muted opacity-50"></i>
                                </div>
                                <h5 class="text-muted mb-2">No Courses Yet</h5>
                                <p class="text-muted mb-4">Ask your parent to purchase some courses for you to start learning!</p>
                                <a href="{{ route('parent.dashboard') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-shopping-cart mr-1"></i>Browse Courses
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        @if($purchases->count() > 0)
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-book"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Courses</span>
                            <span class="info-box-number">{{ $purchases->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-layer-group"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Modules</span>
                            <span class="info-box-number">{{ $purchases->sum(function($p) { return $p->course ? $p->course->modules->count() : 0; }) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-gradient-warning">
                        <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Learning Progress</span>
                            <span class="info-box-number">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
