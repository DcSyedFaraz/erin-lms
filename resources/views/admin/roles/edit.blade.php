@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1>Edit Role</h1>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">← Back to Roles</a>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name">Role Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $role->name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="permissions">Assign Permissions</label>
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Update Role</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
