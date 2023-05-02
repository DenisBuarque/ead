@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.courses.index') }}" class="btn btn-md btn-info" title="Litar registros cadastrados">
            <i class="fa fa-list mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.course.update', ['id' => $course->id]) }}" enctype="multipart/form-data"
            onsubmit="return mySubmit()">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de edição de curso:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <small>Adicione uma imagem ilustrativo do curso no formato JPG, GIF ou PNG</small><br/>
                                <input type="file" name="image" />
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group m-0">
                                <small>Título do curso: *</small>
                                <input type="text" name="title" value="{{ $course->title ?? old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror">
                                @error('title')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group m-0">
                                <small>Polo de ensino: *</small>
                                <select name="polo_id" class="form-control">
                                    @foreach ($polos as $polo)
                                        @if ($polo->id == $course->polo_id)
                                            <option value="{{$polo->id}}" selected>{{$polo->title}}</option>
                                        @else
                                            <option value="{{$polo->id}}">{{$polo->title}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <small>Duração: *</small>
                                <input type="text" name="duration" value="{{ $course->duration ?? old('duration') }}"
                                    placeholder="Ex: 1 ano 3 meses"
                                    class="form-control @error('duration') is-invalid @enderror">
                                @error('duration')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <small>Instituição:</small>
                                <select name="institution" class="form-control">
                                    <option value="setbal" @if ($course->type == 'setbal') selected @endif>Setbal</option>
                                    <option value="ead" @if ($course->type == 'ead') selected @endif>EAD</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <small>Status:</small>
                                <select name="status" class="form-control">
                                    <option value="active" @if ($course->status == 'active') selected @endif>Ativo</option>
                                    <option value="inactive" @if ($course->status == 'inactive') selected @endif>Inativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <small>Descrição do curso:</small>
                                @error('description')
                                    <br/><small class="text-red">{{ $message }}</small>
                                @enderror
                                <textarea name="description" class="form-control" style="width: 100%;">{{ $course->description ?? old('description') }}</textarea>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.courses.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                    <button id="button" type="submit" class="btn btn-md btn-info float-right" style="display: block;">
                        <i class="fas fa-save mr-1"></i>
                        Salvar dados
                    </button>
                    <button type="button" id="spinner" class="btn btn-md btn-info float-right text-center"
                        style="display: none;">
                        <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Enviando...
                    </button>
                </div>
            </div>
        </form>
        <br>
    </div>
@stop

@section('css')

@stop

@section('js')

    <!-- tyneMCE -->
    <script src="https://cdn.tiny.cloud/1/cr3szni52gwqfslu3w63jcsfxdpbitqgg2x8tnnzdgktmhzq/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
            toolbar_mode: 'floating',
        });
    </script>

    <script>
        document.getElementById("button").style.display = "block";
        document.getElementById("spinner").style.display = "none";

        function mySubmit() {
            document.getElementById("button").style.display = "none";
            document.getElementById("spinner").style.display = "block";
        }
    </script>
@stop
