@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1>Edit User</h1>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back to Users</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Name --}}
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="form-control" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="form-control" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Role --}}
                            <div class="mb-3">
                                <label class="form-label">Assign Role</label>
                                <select name="role" class="form-select" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
