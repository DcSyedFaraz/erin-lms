@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="mb-0">{{ $course->title }}</h1>
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary">&larr; Back to Courses</a>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <p><strong>Instructor:</strong> {{ $course->instructor->name }}</p>
                        <p><strong>Category:</strong> {{ $course->category->name }}</p>
                        <p><strong>Level:</strong> {{ $course->level->name }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($course->status) }}</p>
                        @if($course->is_premium)
                            <p><strong>Price:</strong> {{ $course->price }}</p>
                        @endif
                        <p>{{ $course->description }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h5 mb-0">Modules</h2>
                    <a href="{{ route('modules.create', $course) }}" class="btn btn-primary btn-sm">+ Add Module</a>
                </div>

                @forelse ($course->modules as $module)
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $module->title }}</h5>
                        </div>
                    </div>
                @empty
                    <p>No modules yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

