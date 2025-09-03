@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-3 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">Course Summary</h2>
                <p class="text-muted">Quick overview of each course’s content.</p>
            </div>
            <a href="{{ route('parent.courses.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-store mr-1"></i> Browse Courses
            </a>
        </div>
    </div>
    <div class="row">
        @forelse ($courses as $course)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card card-outline card-info h-100">
                    @if($course->thumbnail)
                        <img src="{{ asset("storage/$course->thumbnail") }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $course->title }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="text-muted">{{ \Illuminate\Support\Str::limit($course->description, 140) }}</p>
                        <div class="d-flex justify-content-between text-center my-2">
                            <div>
                                <div class="h5 mb-0">{{ $course->modules_count }}</div>
                                <small class="text-muted">Modules</small>
                            </div>
                            <div>
                                <div class="h5 mb-0">{{ $course->contents_count }}</div>
                                <small class="text-muted">Lessons</small>
                            </div>
                            <div>
                                <div class="h5 mb-0">{{ $course->quizzes_count }}</div>
                                <small class="text-muted">Quizzes</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <span class="badge badge-success">${{ number_format($course->price, 2) }}</span>
                            <div>
                                <a href="{{ route('parent.courses.show', $course) }}" class="btn btn-sm btn-outline-secondary mr-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!isset($purchasedIds) || !in_array($course->id, $purchasedIds))
                                    <button class="btn btn-sm btn-primary btn-buy-modal"
                                        data-course-id="{{ $course->id }}"
                                        data-course-title="{{ $course->title }}"
                                        data-course-price="${{ number_format($course->price, 2) }}">
                                        <i class="fas fa-credit-card mr-1"></i> Buy
                                    </button>
                                @else
                                    <span class="badge badge-secondary">Owned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info mb-0">No courses found.</div>
            </div>
        @endforelse
    </div>
</div>
@include('parent.courses._buy_modal')
@endsection

