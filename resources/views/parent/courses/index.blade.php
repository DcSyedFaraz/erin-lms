@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-3">
                <h2 class="mb-0">Browse Courses</h2>
                <p class="text-muted">Hand-picked lessons your child will love.</p>
            </div>
        </div>
    <div class="row">
        @forelse ($courses as $course)
            <div class="col-md-4 col-sm-6">
                <div class="card card-primary card-outline h-100">
                        @if ($course->thumbnail)
                            <img src="{{ asset("storage/$course->thumbnail") }}" class="card-img-top"
                                style="height: 180px; object-fit: cover;" alt="{{ $course->title }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">{{ $course->title }}</h5>
                            <p class="card-text text-muted" style="flex:1">
                                {{ \Illuminate\Support\Str::limit($course->description, 120) }}</p>
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="badge badge-success">${{ number_format($course->price, 2) }}</span>
                            <button class="btn btn-sm btn-primary btn-buy-modal"
                                data-course-id="{{ $course->id }}"
                                data-course-title="{{ $course->title }}"
                                data-course-price="${{ number_format($course->price, 2) }}">
                                <i class="fas fa-credit-card mr-1"></i> Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
                <div class="col-12">
                    <div class="alert alert-info text-dark mb-0">
                        No courses available to purchase right now.
                    </div>
                </div>
            @endforelse
    </div>
</div>
@include('parent.courses._buy_modal')
@endsection
