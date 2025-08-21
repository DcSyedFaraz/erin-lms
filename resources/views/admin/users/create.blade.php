@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                {{-- Page Header --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="mb-0">Create New User</h1>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back to Users</a>
                </div>

                {{-- Form Start --}}
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf

                            {{-- Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Role --}}
                            <div class="mb-3">
                                <label for="role" class="form-label">Assign Role</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="">-- Select Role --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ old('role') == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </form>
                    </div>
                </div>
                {{-- Form End --}}
            </div>
        </div>
    </div>
@endsection
