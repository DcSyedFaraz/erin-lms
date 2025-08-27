@extends('admin.layout.app')

@section('content')
<div class='container'>
    <h1>Create Module for {{ $course->title ?? 'Course' }}</h1>
    <form method='POST' action='{{ route('modules.store', $course) }}'>
        @csrf
        <div>
            <label>Title</label>
            <input type='text' name='title' required>
        </div>
        <div>
            <label>Description</label>
            <textarea name='description'></textarea>
        </div>
        <div id='texts'>
            <label>Text Contents</label>
            <input type='text' name='text_contents[]'>
        </div>
        <button type='button' onclick='addText()'>Add Text</button>
        <button type='submit'>Next</button>
    </form>
</div>
<script>
function addText(){
    const div=document.getElementById('texts');
    const input=document.createElement('input');
    input.type='text';
    input.name='text_contents[]';
    div.appendChild(input);
}
</script>
@endsection

