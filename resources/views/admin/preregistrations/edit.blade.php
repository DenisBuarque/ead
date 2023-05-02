@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.preregistrations.index') }}" class="btn btn-md btn-info" title="Listar registros cadastrados">
            <i class="fa fa-list mr-1"></i> Listar registro
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.preregistration.update',['id' => $preregistration->id]) }}" onsubmit="return mySubmit()"
            autocomplete="off">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 500px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de edição de pré matrícula:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>
                                Nome: {{ $preregistration->name }}<br/>
                                Telefone: {{ $preregistration->phone }}<br/>
                                E-Mail: {{ $preregistration->email }}<br/>
                                Curso: {{ $preregistration->course->title }}<br/>
                                Criado: {{ \Carbon\Carbon::parse($preregistration->created_at)->format('d/m/Y H:m:s') }}<br/>
                                Atualizado: {{ \Carbon\Carbon::parse($preregistration->updated_at)->format('d/m/Y H:m:s') }}
                            </p>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Pré matrícula:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="active" @if ($preregistration->status == 'active') checked="true" @endif/>
                                    <label class="form-check-label">Confirmada</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="inactive" @if ($preregistration->status == 'inactive') checked="true" @endif/>
                                    <label class="form-check-label">Pendente</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.registrations.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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

@stop
