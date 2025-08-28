@extends('admin.layout.app')

@section('content')
<div class="container">
    <h1>Edit Module</h1>
    <form method="POST" action="{{ route('modules.update', $module) }}">
        @csrf
        @method('PUT')
        <div>
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $module->title) }}" required>
        </div>
        <div>
            <label>Description</label>
            <textarea name="description">{{ old('description', $module->description) }}</textarea>
        </div>
        <button type="submit">Update</button>
    </form>
</div>
@endsection
