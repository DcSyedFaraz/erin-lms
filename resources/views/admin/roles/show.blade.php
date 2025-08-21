@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1>Role Details</h1>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">← Back to Roles</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5><strong>Name:</strong> {{ $role->name }}</h5>
                        <p><strong>Created At:</strong> {{ $role->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
