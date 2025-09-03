@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-3 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-0">My Courses</h2>
                <p class="text-muted">Access everything you’ve purchased.</p>
            </div>
            <a href="{{ route('parent.courses.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-store mr-1"></i> Browse More Courses
            </a>
        </div>
    </div>
    <div class="row">
        @forelse ($purchases as $purchase)
            @php($course = $purchase->course)
            <div class="col-md-4 col-sm-6">
                <div class="card card-success card-outline h-100">
                    @if($course && $course->thumbnail)
                        <img src="{{ asset("storage/$course->thumbnail") }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $course->title }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2">{{ $course->title }}</h5>
                        <p class="card-text text-muted" style="flex:1">{{ \Illuminate\Support\Str::limit($course->description, 120) }}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge badge-success">Owned</span>
                            <a href="{{ route('parent.courses.show', $course) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-play mr-1"></i> Go To Course
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-dark mb-0">
                    You haven’t purchased any courses yet.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

