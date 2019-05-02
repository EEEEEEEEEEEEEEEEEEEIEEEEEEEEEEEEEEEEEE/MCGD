@extends('layouts.app')
@section('css')
<link href="{{ asset('css/main.min.css') }}" rel="stylesheet">
    <style>
        @media screen and (min-width: 1000px) {
            .ppf {position: fixed;top:10%;right:0;height:90%;}
        }
    </style>
@endsection
@section('js')
<script src="{{ asset('js/ace.js') }}" ></script>
<script src="{{ asset('js/mode-json.js') }}" ></script>
<script src="{{ asset('js/theme-dracula.js') }}" ></script>
<script src="{{ asset('js/worker-json.js') }}" ></script>
@endsection
@section('content')

    <div class="container">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    代码编辑
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
        <div class="col-md-4 ppf" style="height:90%;">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <button id="sub" class="btn btn-primary">保存</button>
                    <button id="vie" class="btn btn-primary">预览</button>
                </div>

                <div class="card-body" style="height: 80%;overflow-y: scroll;">
                    <img id="img" style="width:100%;"/>
                    <div id="debug"></div>
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
                    if(xhr.responseText){
						try{
							var json=JSON.parse(xhr.responseText);
						}catch(e) {
							document.getElementById('debug').innerHTML=xhr.responseText;
						}
                        document.getElementById('img').src=json.url;
                        var f1='<table border="1"><tr><th>Key</th><th>Value</th></tr>';
                        for(let k in json.replace) {
                            f1+='<tr><th>'+htmlEncode(k)+'</th><th>'+htmlEncode(json.replace[k])+'</th></tr>';
                        }
                        for(let k in json.img) {
                            f1+='<tr><th>'+htmlEncode(k)+'</th><th><img src="'+json.img[k]+'"/></th></tr>';
                        }
                        f1+='</table>';
                        document.getElementById('debug').innerHTML=f1;
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
    function htmlEncode(str) {
        var div = document.createElement("tteemmpp");
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }
</script>
@endsection
