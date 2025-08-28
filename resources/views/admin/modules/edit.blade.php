@extends('admin.layout.app')

@section('content')
<div class="container">
    <h1>Edit Module</h1>
    <form method="POST" action="{{ route('modules.update', $module) }}" enctype="multipart/form-data">
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

        <div id="texts">
            <label>Text Contents</label>
            @foreach($module->contents->where('type', 'text') as $content)
                <input type="text" name="text_contents[]" value="{{ old('text_contents.' . $loop->index, $content->text) }}">
            @endforeach
        </div>
        <button type="button" onclick="addText()">Add Text</button>

        <div>
            <label>Existing Files</label>
            <ul>
                @foreach($module->contents->where('type', '!=', 'text') as $content)
                    <li>
                        <a href="{{ Storage::url($content->path) }}" target="_blank">{{ ucfirst($content->type) }}</a>
                        <label>
                            <input type="checkbox" name="remove_contents[]" value="{{ $content->id }}"> Remove
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <label>PDF Files</label>
            <input type="file" name="pdfs[]" accept=".pdf" multiple>
        </div>
        <div>
            <label>Images</label>
            <input type="file" name="images[]" accept="image/*" multiple>
        </div>
        <div>
            <label>Videos</label>
            <input type="file" name="videos[]" accept="video/*" multiple>
        </div>

        <button type="submit">Update</button>
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
