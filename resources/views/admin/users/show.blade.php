@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1>User Details</h1>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back to Users</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Role:</strong> {{ $user->getRoleNames()->implode(', ') }}</p>
                        <p><strong>Created At:</strong> {{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
