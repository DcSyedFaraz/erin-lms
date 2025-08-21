@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Heading and Create Button --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="mb-0">Users List</h1>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        + Create User
                    </a>
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- DataTable --}}
                <table id="usersTable" class="display table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">Show</a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
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
            $('#usersTable').DataTable();
        });
    </script>
@endsection
