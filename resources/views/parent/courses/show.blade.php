@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-lg-8">
            <div class="card">
                @if($course->thumbnail)
                    <img src="{{ asset("storage/$course->thumbnail") }}" class="card-img-top" style="max-height: 320px; object-fit: cover;" alt="{{ $course->title }}">
                @endif
                <div class="card-body">
                    <h3 class="card-title mb-2">{{ $course->title }}</h3>
                    <p class="text-muted mb-0">{{ $course->description }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h5 class="card-title mb-0">Course Modules</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($course->modules as $module)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-book mr-2 text-primary"></i>{{ $module->title }}</span>
                                <span class="badge badge-light">#{{ $module->order }}</span>
                            </li>
                        @empty
                            <li class="list-group-item">No modules yet.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('parent.courses.my') }}" class="btn btn-outline-secondary">Back to My Courses</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

