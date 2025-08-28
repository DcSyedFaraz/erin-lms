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

                        <p><strong>Category:</strong> {{ $course->category->name }}</p>
                        <p><strong>Level:</strong> {{ $course->level->name }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($course->status) }}</p>
                        @if ($course->is_premium)
                            <p><strong>Price:</strong> {{ $course->price }}</p>
                        @endif
                        <p>{{ $course->description }}</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h5 mb-0">Modules</h2>
                    <a href="{{ route('modules.create', $course) }}" class="btn btn-primary btn-sm">+ Add Module</a>
                </div>

                <ul id="modules-list" class="list-unstyled">
                    @forelse ($course->modules as $module)
                        <li class="card mb-2 module-item" data-id="{{ $module->id }}">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <span class="handle mr-2"><i class="fas fa-arrows-alt"></i></span>
                                <span class="flex-grow-1">{{ $module->title }}</span>
                                <div>
                                    <a href="{{ route('modules.show', $module) }}" class="btn btn-info btn-sm">Show</a>
                                    <a href="{{ route('modules.edit', $module) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('modules.destroy', $module) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @empty
                        <p>No modules yet.</p>
                    @endforelse
                </ul>
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
