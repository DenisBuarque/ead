@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1 class="m-0">Atendimento ao Aluno</h1>
        <a href="{{ route('classroom.customer.service') }}" class="btn btn-md btn-danger" title="Sair da sala de aula.">
            <i class="fa fa-times mr-1"></i> Sair do atendimento
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('classroom.store.atendiment') }}" onsubmit="return mySubmit()" autocomplete="off">
            @csrf
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de solicitação de atendimento:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <small>Status:</small>
                                <select name="status" class="form-control" disabled>
                                    <option value="open" selected>Abrir ticket</option>
                                    <option value="pending">Ticket pendente</option>
                                    <option value="resolved">Ticket resolvido</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <small>Atendimento:</small>
                                <select name="sector" class="form-control">
                                    <option value="support" selected>Suporte</option>
                                    <option value="academic">Acadêmico</option>
                                    <option value="financial">Financeiro</option>
                                    <option value="complaint">Ouvidoria</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group m-0">
                                <small>Assunto: *</small>
                                <input type="text" name="subject" maxlength="200" required
                                    class="form-control @error('subject') is-invalid @enderror"/>
                                @error('subject')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" required
                                    placeholder="Deixe aqui seu texto sobre o que deseja falar conosco, que em breve entraremos em contato."
                                    style="width: 100%; height: 250px;">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('classroom.customer.service') }}" type="submit" class="btn btn-default">Cancelar</a>
                    <button id="button" type="submit" class="btn btn-md btn-info float-right" style="display: block;">
                        <i class="fa fa-paper-plane mr-1"></i>
                        Enviar solicitação de atendimento
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
