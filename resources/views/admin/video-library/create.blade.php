@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Add Video Library Item</h1>
            <a href="{{ route('admin.video-library.index') }}" class="btn btn-secondary">Back to Library</a>
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
                <form action="{{ route('admin.video-library.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.video-library._form')
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Save Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
