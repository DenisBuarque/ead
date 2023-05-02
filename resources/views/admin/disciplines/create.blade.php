@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.disciplines.index') }}" class="btn btn-md btn-info" title="Listar disciplinas cadastradas">
            <i class="fa fa-list mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{route('admin.discipline.store')}}" onsubmit="return mySubmit()" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de cadastro de disciplina:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Instituição: *</small>
                                <select name="institution" class="form-control @error('institution') is-invalid @enderror">
                                    <option value=""></option>
                                    <option value="setbal">Setbal</option>
                                    <option value="ead">EAD</option>
                                </select>
                                @error('institution')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Curso: *</small>
                                <select name="course_id" class="form-control @error('title') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach ($courses as $course)
                                        <option value="{{$course->id}}">{{$course->title}} ({{ $course->institution }})</option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group m-0">
                                <small>Título da disciplina: *</small>
                                <input type="text" name="title" value="{{ old('title') }}" maxlength="100" class="form-control @error('title') is-invalid @enderror">
                                @error('title')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Professor:</small>
                                <select name="user_id" class="form-control">
                                    <option value=""></option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Ano:</small>
                                <select name="year" class="form-control">
                                    <option value=""></option>
                                    <option value="1">1º ano</option>
                                    <option value="2">2º ano</option>
                                    <option value="3">3º ano</option>
                                    <option value="4">4º ano</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Semestre:</small>
                                <select name="semester" class="form-control">
                                    <option value=""></option>
                                    <option value="1">1º semestre</option>
                                    <option value="2">2º semestre</option>
                                    <option value="3">3º semestre</option>
                                    <option value="4">4º semestre</option>
                                    <option value="5">5º semestre</option>
                                    <option value="6">6º semestre</option>
                                    <option value="7">7º semestre</option>
                                    <option value="8">8º semestre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Carga Horária:</small>
                                <input type="text" name="workload" value="{{ old('workload') }}" maxlength="50" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Período:</small>
                                <input type="text" name="period" value="{{ old('period') }}" maxlength="50" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Créditos:</small>
                                <input type="text" name="credits" value="{{ old('credits') }}" maxlength="50" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Teste avaliativo:</small>
                                <select name="quiz" class="form-control">
                                    <option value="active" selected>Bloqueado</option>
                                    <option value="inactive">Liberado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-0">
                                <small>Status:</small>
                                <select name="status" class="form-control">
                                    <option value="active" selected>Ativo</option>
                                    <option value="inactive">Inativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>Descrição da disciplina: *</small>
                                @error('description')
                                    <br/><small class="text-red">{{ $message }}</small>
                                @enderror
                                <textarea name="description" class="form-control" style="width: 100%;" placeholder="Descreva aqui o que o aluno irá aprender nessa disciplina.">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.disciplines.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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
    <script src="https://cdn.tiny.cloud/1/cr3szni52gwqfslu3w63jcsfxdpbitqgg2x8tnnzdgktmhzq/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
          selector: 'textarea',
          plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
          toolbar_mode: 'floating',
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
