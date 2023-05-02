@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.multiplequestions.index') }}" class="btn btn-md btn-info" title="Listar registros cadastrados">
            <i class="fa fa-list mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.multiplequestion.update',['id' => $question->id]) }}" autocomplete="off" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de edição de dados questão multipla:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group m-0">
                                <small>Curso: *</small>
                                <select name="course_id" id="course" class="form-control">
                                    <option value=""></option>
                                    @foreach ($courses as $course)
                                        @if ($question->course_id == $course->id)
                                            <option value="{{ $course->id }}" selected>{{ $course->title }} ({{ $course->institution }})</option>
                                        @else
                                            <option value="{{ $course->id }}">{{ $course->title }}({{ $course->institution }})</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
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
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Gabarito resposta: *</small>
                                <select name="gabarito" class="form-control @error('gabarito') is-invalid @enderror">
                                    <option value="1" @if ($question->gabarito == 1 or old('gabarito') == 1) selected @endif>1</option>
                                    <option value="2" @if ($question->gabarito == 2 or old('gabarito') == 2) selected @endif>2</option>
                                    <option value="3" @if ($question->gabarito == 3 or old('gabarito') == 3) selected @endif>3</option>
                                    <option value="4" @if ($question->gabarito == 4 or old('gabarito') == 4) selected @endif>4</option>
                                </select>
                                @error('gabarito')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                                @error('gabarito')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group m-0">
                                <small>Pontuação: *</small>
                                <input type="number" name="punctuation" value="{{ $question->punctuation ?? old('punctuation') }}" max="100" min="1" class="form-control @error('punctuation') is-invalid @enderror">
                                @error('punctuation')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>Pergunta:</small>
                                <textarea name="title" class="form-control @error('title') is-invalid @enderror" style="width: 100%;"
                                    placeholder="Descreva aqui a pergunta da questão.">{{ $question->title ?? old('title') }}</textarea>
                                @error('title')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>1º Resposta:</small>
                                <textarea name="response_one" class="form-control @error('response_one') is-invalid @enderror" style="width: 100%;"
                                    placeholder="Descreva aqui a primeira resposta da pergunta para escolha do aluno.">{{ $question->response_one ?? old('response_one') }}</textarea>
                                @error('response_one')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>2º Resposta:</small>
                                <textarea name="response_two" class="form-control @error('response_two') is-invalid @enderror" style="width: 100%;"
                                    placeholder="Descreva aqui a segunda resposta da pergunta para escolha do aluno.">{{ $question->response_two ?? old('response_two') }}</textarea>
                                @error('response_two')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>3º Resposta:</small>
                                <textarea name="response_tree" class="form-control @error('response_tree') is-invalid @enderror" style="width: 100%;"
                                    placeholder="Descreva aqui a terceira resposta da pergunta para escolha do aluno.">{{ $question->response_tree ?? old('response_tree') }}</textarea>
                                @error('response_tree')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>4º Resposta:</small>
                                <textarea name="response_four" class="form-control @error('response_four') is-invalid @enderror" style="width: 100%;"
                                    placeholder="Descreva aqui a quarta resposta da pergunta para escolha do aluno.">{{ $question->response_four ?? old('response_four') }}</textarea>
                                @error('response_four')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.multiplequestions.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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

    <script>
        $('#course').change(function() {

            $("#discipline option").remove();
            
            var course_id = $(this).val();

            if (course_id !== "") 
            {
                $.ajax({
                    type: 'GET',
                    url: "/admin/multiplequestions/list/" + course_id,
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
                url: "/admin/multiplequestions/list/{{ $question->id }}",
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
