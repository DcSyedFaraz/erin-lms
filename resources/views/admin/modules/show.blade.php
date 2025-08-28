@extends('admin.layout.app')
@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container">
    <h1>{{ $module->title }}</h1>
    <p>{{ $module->description }}</p>

    <h3>Contents</h3>
    <ul>
        @forelse($module->contents as $content)
            <li>
                @if($content->type === 'text')
                    {{ $content->text }}
                @else
                    <a href="{{ Storage::url($content->path) }}" target="_blank">{{ ucfirst($content->type) }} file</a>
                @endif
            </li>
        @empty
            <li>No contents.</li>
        @endforelse
    </ul>

    <h3>Quizzes</h3>
    <ul>
        @forelse($module->quizzes as $quiz)
            <li>{{ $quiz->question }}</li>
        @empty
            <li>No quizzes.</li>
        @endforelse
    </ul>

    <a href="{{ route('modules.edit', $module) }}" class="btn btn-warning btn-sm">Edit</a>
    <form action="{{ route('modules.destroy', $module) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
    </form>
    <a href="{{ route('courses.show', $module->course) }}" class="btn btn-secondary btn-sm">Back</a>
</div>
@endsection
