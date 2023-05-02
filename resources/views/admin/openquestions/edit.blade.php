@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.openquestions.index') }}" class="btn btn-md btn-info" title="Listar registros cadastrados">
            <i class="fa fa-list mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.openquestion.update', ['id' => $question->id]) }}" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de edição questão aberta:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-0">
                                <small>Curso: *</small>
                                <select name="course_id" id="course" class="form-control">
                                    <option value=""></option>
                                    @foreach ($courses as $course)
                                        @if ($question->course_id == $course->id)
                                            <option value="{{ $course->id }}" selected>{{ $course->title }} ({{ $course->institution }})</option>
                                        @else
                                            <option value="{{ $course->id }}">{{ $course->title }} ({{ $course->institution }})</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-0">
                                <small>Disciplina: *</small>
                                <select name="discipline_id" id="discipline" class="form-control">
                                    @foreach ($disciplines as $discipline)
                                        @if ($question->discipline_id == $discipline->id)
                                            <option value="{{ $discipline->id }}" selected>{{ $discipline->title }} ({{ $discipline->institution }})</option>
                                        @else
                                            <option value="{{ $discipline->id }}">{{ $discipline->title }} ({{ $discipline->institution }})</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('discipline_id')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>Título da questão:</small>
                                <textarea name="title" class="form-control @error('title') is-invalid @enderror" style="width: 100%;"
                                    placeholder="Descreva aqui a pergunta da questão.">{{ $question->title ?? old('title') }}</textarea>
                                @error('title')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.openquestions.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

    <script>
        $('#course').change(function() {

            $("#discipline option").remove();

            var course_id = $(this).val();

            if (course_id !== "") {
                $.ajax({
                    type: 'GET',
                    url: "/admin/openquestions/list/" + course_id,
                    dataType: 'html',
                    success: function(response) {
                        $("#discipline").append(response);
                    }
                })
            }
        });

        $('#discipline').ready(function() {
            $.ajax({
                type: 'GET',
                url: "/admin/openquestions/list/{{ $question->course_id }}",
                dataType: 'html',
                success: function(response) {
                    $("#discipline").append(response);
                }
            })
        });
    </script>

    <!-- butons -->
    <script>
        document.getElementById("button").style.display = "block";
        document.getElementById("spinner").style.display = "none";

        function mySubmit() {
            document.getElementById("button").style.display = "none";
            document.getElementById("spinner").style.display = "block";
        }
    </script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

@stop
