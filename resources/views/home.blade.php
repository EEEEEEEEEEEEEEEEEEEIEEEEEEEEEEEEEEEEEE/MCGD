@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <pre id="editor"></pre>
            </div>
        </div>
    </div>
</div>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/dracula");
    editor.session.setMode("ace/mode/json");
    editor.renderer.setScrollMargin(10, 10);
    editor.setOptions({
        autoScrollEditorIntoView: true
    });
    var element = document.getElementById('editor_holder')
    var editor = new JSONEditor(element, {
        theme: 'bootstrap2'
    });
</script>
@endsection
