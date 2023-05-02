@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.customerservices.index') }}" class="btn btn-md btn-info" title="Listar registros cadastrados">
            <i class="fa fa-list mr-1"></i> Listar registros
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('admin.customerservice.update', ['id' => $customerservice->id]) }}"
            onsubmit="return mySubmit()" autocomplete="off">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" value="{{ $customerservice->user_id }}" />
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário de edição de atendimento ao público:</h3>
                </div>
                <div class="card-body">

                    <div class="d-flex">
                        

                        @if (isset($customerservice->user->image))
                                        <img src="{{ asset('storage/' . $customerservice->user->image) }}" alt="Photo"
                                            style="width: 70px; height: 70px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="{{ asset('images/not-photo.jpg') }}" alt="Photo"
                                            style="width: 70px; height: 70px;" class="img-circle img-size-32 mr-2">
                                    @endif

                        <div>
                            Aluno: <strong>{{ $customerservice->user->name }}</strong><br />
                            Telefone: {{ $customerservice->user->phone }}<br />
                            E-mail: {{ $customerservice->user->email }}<br />
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <small>Atendimento:</small>
                                <select name="status" class="form-control">
                                    <option value="open" @if ($customerservice->status == 'open') selected @endif>Aberto</option>
                                    <option value="pending" @if ($customerservice->status == 'pending') selected @endif>Pendente</option>
                                    <option value="resolved" @if ($customerservice->status == 'resolved') selected @endif>Resolvido</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <small>Setor:</small>
                                <select name="sector" class="form-control">
                                    <option value="support" @if ($customerservice->sector == 'support') selected @endif>Suporte
                                    </option>
                                    <option value="academic" @if ($customerservice->sector == 'academic') selected @endif>Acadêmico
                                    </option>
                                    <option value="financial" @if ($customerservice->sector == 'financial') selected @endif>Financeiro
                                    </option>
                                    <option value="complaint" @if ($customerservice->sector == 'complaint') selected @endif>Reclamação
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group m-0">
                                <small>Assunto: *</small>
                                <input type="text" name="subject"
                                    value="{{ $customerservice->subject ?? old('subject') }}" maxlength="200"
                                    class="form-control @error('subject') is-invalid @enderror">
                                @error('subject')
                                    <small class="text-red line-height">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group m-0">
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Deixe aqui seu texto sobre o que deseja falar conosco, que em breve entraremos em contato."
                                    style="width: 100%; height: 250px;">{{ $customerservice->description ?? old('description') }}</textarea>
                                @error('description')
                                    <small class="text-red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.customerservices.index') }}" type="submit"
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
