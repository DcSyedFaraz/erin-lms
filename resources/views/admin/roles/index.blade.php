@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Roles List</h1>
            <a href="{{ route('roles.create') }}" class="btn btn-primary">+ Create Role</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table id="rolesTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('roles.show', $role->id) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable();
        });
    </script>
@endsection
