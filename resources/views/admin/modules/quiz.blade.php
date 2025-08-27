@extends('admin.layout.app')

@section('content')
    <div class='container'>
        <h1>Create Quiz for {{ $module->title }}</h1>
        <form method='POST' action='{{ route('modules.quiz.store', $module) }}'>
            @csrf
            <div>
                <label>Question</label>
                <input type='text' name='question' required>
            </div>
            <div>
                <label>Type</label>
                <select name='type'>
                    <option value='multiple_choice'>Multiple Choice</option>
                    <option value='true_false'>True / False</option>
                </select>
            </div>
            <div>
                <label>Options (comma separated for MCQ)</label>
                <input type='text' name='options[]'>
                <input type='text' name='options[]'>
                <input type='text' name='options[]'>
                <input type='text' name='options[]'>
            </div>
            <div>
                <label>Answer</label>
                <input type='text' name='answer' required>
            </div>
            <button type='submit'>Save Module</button>
        </form>
    </div>
@endsection
