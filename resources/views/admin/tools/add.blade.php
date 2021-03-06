@extends('adminlte::page')

@section('title', 'Add new tool')

@section('content_header')
    <h1>Add new tool</h1>
    <br>
    @if(Session::has('message'))
        <div class="alert alert-{{Session::get('level')}}">
            {{Session::get('message')}}
        </div>
    @endif

@stop

@section('content')
    <form action="{{route('tool.store')}}" method="post" role="form">
        @csrf
        @if(count($errors)>0)
            <ol>
                @foreach($errors->all() as $err)
                    <li class=" text-warning" style="margin-bottom: 5px">
                        {{$err}}
                    </li>
                @endforeach
            </ol>
        @endif
        <div class="form-group">
            <label for="game">Game</label>
            <select class="form-control" id="game" required name="game_id">
                @if(count($games) > 0)
                    @foreach($games as $game)
                        <option value="{{$game->id}}">{{$game->name}}</option>
                    @endforeach
                @else
                    <option value="null">-- No game found</option>
                @endif
            </select>
        </div>

        <div class="form-group">
            <label for="">Tool name</label>
            <input type="text" class="form-control" name="name" id="" placeholder="Hack pubg.." required>
        </div>
        <div class="form-group">
            <label for="">Note</label>
            <input type="text" class="form-control" name="note" id="" placeholder="Note">
        </div>
        <div class="form-group">
            <label for="">Logo</label>
            <input type="text" class="form-control" name="logo" id="" placeholder="logo" required>
        </div>
		<div class="form-group">
            <label for="">Slide</label>
            <textarea type="text" class="form-control" name="images" id="images" required placeholder="Mỗi link ảnh trên 1 dòng"></textarea>
        </div>
        <div class="form-group">
            <label for="">Download link</label>
            <input type="text" class="form-control" name="link" id="" required>
        </div>
        <div class="form-group">
            <label for="">Backup link</label>
            <input type="text" class="form-control" name="link_backup" id="">
        </div>
        <div class="form-group">
            <label for="cost">Tool input price (each pack 1 line written in the form 12=3000) </label>
            <textarea id="cost" name="cost" class="form-control" rows="4">{{ old('cost') }}</textarea>
        </div>
        <div class="form-group">
            <label for="package">Packages of the tool (each pack 1 line written in the form 12=3000) </label>
            <textarea id="package" name="package" class="form-control" rows="4">{{ old('package') }}</textarea>
        </div>
        <div class="form-group">
            <label for="cost">Packages sold to agents (each pack has 1 line written in the form 12=30000) </label>
            <textarea id="reseller" name="reseller" class="form-control" rows="4">{{ old('reseller') }}</textarea>
        </div>

        <div class="form-group">
            <label for="thumb_image">Tool usage link</label>
            <input type="text" class="form-control" name="youtube" id="" placeholder="ID Video Youtube" required>
        </div>
		<div class="form-group">
            <label for="showcase">Video showcase ID</label>
            <input type="text" class="form-control" name="showcase" id="showcase" placeholder="ID on youtube" required>
        </div>
        <div class="form-group">
            <label for="discount">Discount(%)</label>
            <input type="number" class="form-control" name="discount" id="discount" placeholder="Discount percent">
        </div>
		<!--
		<div class="form-group">
            <label for="error_code">Các mã lỗi. Ví dụ: 01=Nội dung lỗi</label>
            <textarea id="error_code" name="error_code" class="form-control" rows="4">{{ old('error_code') }}</textarea>
        </div>
        <div class="form-group">
            <label for="">Mô tả ngắn</label>
            <input type="text" class="form-control" name="description" id="" placeholder="Hack pubg.." required>
        </div>
        <div class="form-group">
            <label for="description_eng">Mô tả ngắn (tiếng Anh)</label>
            <input type="text" class="form-control" name="description_eng" id="description_eng" placeholder="Hack pubg..">
        </div>

        <div class="form-group">
            <label for="content">Hướng dẫn sử dụng</label>
            <textarea id="content" name="content" rows="10" class="form-control my-editor">{{ old('content') }}</textarea>
        </div>
        <div class="form-group">
            <label for="content">Hướng dẫn sử dụng (tiếng Anh)</label>
            <textarea id="content" name="content_eng" rows="10" class="form-control my-editor">{{ old('content_eng') }}</textarea>
        </div>
		-->
        <div class="form-group">
            <label for="">Order number</label>
            <input type="number" class="form-control" name="order" id="" value="99" required>
        </div>
		<div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>

            <select class="form-control" name="author">
                <option value="me">Owner</option>
                <option value="rent">Hire</option>
            </select>
        </div>
        <div class="form-group">
            <div class="radio">
                <label><input type="checkbox" name="active" checked> Show tool?</label>
            </div>
        </div>

        <div class="form-group">
            <div class="radio">
                <label><input type="checkbox" name="updated" checked> Updated?</label>
            </div>
        </div>
        <br>
        @if(count($games) > 0)
            <a href="{{URL::previous()}}" class="btn btn-warning">BACK</a>
            <button type="submit" class="btn btn-success pull-right" style="width: 90px">SAVE</button>
        @else
            <h3>Please add game first !</h3>
        @endif
    </form>
@stop

@section('js')
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

    <script>

        var editor_config = {
            path_absolute: "/",
            selector: "textarea.my-editor",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | forecolor backcolor | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",

            relative_urls: false,
            file_browser_callback: function (field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no"
                });
            }
        };

        tinymce.init(editor_config);
    </script>

@stop
