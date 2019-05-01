@extends('layouts.app')
@section('css')
<link href="{{ asset('css/main.min.css') }}" rel="stylesheet">
@endsection
@section('js')
<script src="{{ asset('js/ace.js') }}" ></script>
<script src="{{ asset('js/mode-json.js') }}" ></script>
<script src="{{ asset('js/theme-dracula.js') }}" ></script>
<script src="{{ asset('js/worker-json.js') }}" ></script>
@endsection
@section('content')
    <img id="img"/>
    <div class="container">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <button id="sub" class="btn btn-primary">保存</button>
                        <button id="vie" class="btn btn-primary">预览</button>
                    </div>

                    <div class="card-body">
                        <form id="mainform" action="{{ Route('home') }}" method="post">
                             @csrf
                            <pre id="editor" style="width:100%">{{ $server_data }}</pre>
                            <input id="data" name="data" type="hidden">
                        </form>
                    </div>
                </div>
            </div>
    </div>
<script defer>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/dracula");
    editor.session.setMode("ace/mode/json");
    editor.renderer.setScrollMargin(10, 10);
    editor.setOptions({
        autoScrollEditorIntoView: true,
        wrap: 'free'
    });
    document.getElementById("sub").addEventListener('click',function () {
        try {
            var obj=JSON.parse(editor.getValue());
            if(typeof obj == 'object' && obj ){
                document.getElementById("data").value=editor.getValue();
                document.getElementById('mainform').submit();
            }else{
                alert("格式不正确");
            }
        } catch(e) {
            alert("格式不正确");
        }
    });
    document.getElementById("vie").addEventListener('click',function () {
        try {
            var obj=JSON.parse(editor.getValue());
            if(typeof obj == 'object' && obj ){
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function(){
                    if(xhr.readyState == 4){
                        document.getElementById('img').src=xhr.responseText;
                    }
                };
                xhr.open('POST','{{ route('base64') }}',true);
                xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                xhr.send(
                    'data='+encodeURI(editor.getValue())+
                    '&_token='+encodeURI('{{ csrf_token() }}')
                );
            }else{
                alert("格式不正确");
            }
        } catch(e) {
            alert("格式不正确");
        }
    });
</script>
@endsection
