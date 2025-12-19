@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Heading and Create Button --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Courses List</h4>
                    <a href="{{ route('courses.create') }}" class="btn btn-primary">
                        + Create Course
                    </a>
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <table id="coursesTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $course)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $course->title }}</td>
                                <td>{{ ucfirst($course->status) }}</td>
                                <td>{{ $course->category->name }}</td>
                                <td>
                                    <a href="{{ route('courses.show', $course->id) }}"
                                        class="btn btn-info btn-sm">View</a>

                                    @if ($course->status != 'published')
                                        <form action="{{ route('courses.approve', $course) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                    @endif

                                    @if ($course->status != 'draft')
                                        <form action="{{ route('courses.archive', $course) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Archive</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('courses.destroy', $course) }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this course?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#coursesTable').DataTable();
        });
    </script>
@endsection
