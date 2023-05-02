@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-md btn-info" title="Listar registros cadastrados">
            <i class="fa fa-list mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.inscription.update',['id' => $inscription->id]) }}" onsubmit="return mySubmit()" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 500px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário edição de inscrição de aluno:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>Curso: *</small>
                                <select name="course_id" id="course" class="form-control @error('course_id') is-invalid @enderror">
                                    <option value="">Selecione um curso</option>
                                    @foreach ($courses as $course)
                                        @if ($inscription->course_id == $course->id)
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
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>Disciplina: *</small>
                                <select name="discipline_id" id="discipline" class="form-control @error('discipline_id') is-invalid @enderror">
                                    @foreach ($disciplines as $discipline)
                                        @if ($discipline->id == $inscription->discipline_id)
                                            <option value="{{ $discipline->id }}" selected>{{ $discipline->title }}</option>
                                        @else
                                            <option value="{{ $discipline->id }}">{{ $discipline->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('discipline_id')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 m-0">
                            <div class="form-group">
                                <small>Aluno(a): *</small>
                                <select name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                    <option value="">Selecione um aluno</option>
                                    @foreach ($students as $student)
                                        @if ($inscription->user_id == $student->id)
                                            <option value="{{ $student->id }}" selected>{{ $student->name }}</option>
                                        @else
                                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <small>Data de inscrição: *</small>
                                <input type="date" name="date_inscription" value="{{ $inscription->date_inscription ?? old('date_inscription') }}"
                                class="form-control @error('date_inscription') is-invalid @enderror">
                                @error('date_inscription')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <small>Data de encerramento: *</small>
                                <input type="date" name="closing_date" value="{{ $inscription->closing_date ?? old('closing_date') }}"
                                class="form-control @error('closing_date') is-invalid @enderror">
                                @error('closing_date')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="pendente" @if ($inscription->status == 'pendente') checked="true" @endif />
                                    <label class="form-check-label">Aguardando pagamento da inscrição.</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="pago" @if ($inscription->status == 'pago') checked="true" @endif />
                                    <label class="form-check-label">Pagamento realizado.</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.inscriptions.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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

    <script>
        $('#course').change(function() {

            $("#discipline option").remove();
            
            var course_id = $(this).val();

            if (course_id !== "") 
            {
                $.ajax({
                    type: 'GET',
                    url: "/admin/inscription/list/" + course_id,
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
                url: "/admin/inscription/list/{{ $inscription->course_id }}",
                dataType: 'html',
                success: function(response) {
                    $("#discipline").append(response);
                }
            })
        });
    </script>

@stop
