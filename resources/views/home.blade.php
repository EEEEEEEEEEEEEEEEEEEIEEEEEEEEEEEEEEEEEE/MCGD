@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <textarea id="editor_holder">{{ $server_data }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var element = document.getElementById('editor_holder')
    var editor = new JSONEditor(element, {
        theme: 'bootstrap2'
    });
</script>
@endsection
