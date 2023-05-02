@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.discipline.show', ['id' => $discipline->id]) }}" class="btn btn-md btn-info"
            title="Voltar aos módulos da disciplina">
            <i class="fa fa-undo mr-1"></i> Voltar aos módulos
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.module.store.movie') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <input type="hidden" name="discipline_id" value="{{ $discipline->id }}" />
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de cadastro de vídeo
                        <strong>{{ $discipline->title }}</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <small>Título do vídeo: *</small>
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror" maxlength="200" />
                                @error('title')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group m-0">

                                <span>Para adicionar o vídeo selecione o código com mostra o link de exemplo https://www.youtube.com/watch?v=<label>oy4cbqE1_qc<label></span>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <img src="/images/youtube.png" style="width: 20px; height: 20px"
                                                alt="icone" />
                                        </span>
                                    </div>
                                    <input type="text" name="movie" value="{{ old('movie') }}" placeholder="Ex: oy4cbqE1_qc"
                                        class="form-control @error('movie') is-invalid @enderror" required />
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <small>Descrição do vídeo:</small>
                                <textarea name="description" class="form-control  @error('description') is-invalid @enderror" required
                                    style="width: 100%;">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.discipline.show', ['id' => $discipline->id]) }}" type="submit"
                        class="btn btn-default">Cancelar</a>
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
