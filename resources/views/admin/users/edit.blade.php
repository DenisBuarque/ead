@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.users.index') }}" class="btn btn-md btn-info" title="Listar registros">
            <i class="fa fa-list mr-1"></i> Listar registros
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">

        <form method="POST" action="{{ route('admin.user.update', ['id' => $user->id]) }}" enctype="multipart/form-data"
            onsubmit="return mySubmit()">
            @csrf
            @method('PUT')
            <div class="card card-info" style="max-width: 1024px; margin: auto">
                <div class="card-header">
                    <h3 class="card-title">Formulário edição de dados de administradores do sistema:</h3>
                </div>
                <div class="card-body">

                    <div class="widget-user-image">
                        @if (isset($user->image))
                            <img class="img-circle img-thumbnail" style="width: 100px; height: 100px;"
                                src="{{ asset('storage/' . $user->image) }}" alt="User Avatar">
                        @else
                            <img class="img-circle img-thumbnail" style="width: 100px; height: 100px;"
                                src="{{ asset('images/not-photo.jpg') }}" alt="User Avatar">
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <small>Foto do usuário:</small>
                            <div class="form-group">
                                <input type="file" name="image" />
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group m-0">
                                <small>Nome do usuário: *</small>
                                <input type="text" name="name" value="{{ $user->name ?? old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group m-0">
                                <small>Telefone para contato: *</small>
                                <input type="text" name="phone" value="{{ $user->phone ?? old('phone') }}"
                                placeholder="(00)00000-0000" onkeyup="handlePhone(event)" maxlength="15" class="form-control" />
                                @error('phone')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <small>E-mail: *</small>
                                <input type="email" name="email" value="{{ $user->email ?? old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <small>Senha:</small>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="******">
                                @error('password')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <small>Confirme sua senha:</small>
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="******">
                                @error('password_confirmation')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-12 m-0">
                            <div class="form-group">
                                <small>Permissões de acesso ao sistema: pressione e segure a tecla 'Ctrl',
                                    em seguida clique sobre a opção para selecionar.</small>
                                <select name="permission[]" class="form-control" multiple style="height: 170px;">
                                    @foreach ($permissions as $key => $value)
                                        @php
                                            $selected = '';
                                            if (old('permission')):
                                                foreach (old('permission') as $key => $value2):
                                                    if ($value->id == $value2):
                                                        $selected = 'selected';
                                                    endif;
                                                endforeach;
                                            else:
                                                if ($user) {
                                                    foreach ($user->permissions as $key => $permission):
                                                        if ($permission->id == $value->id):
                                                            $selected = 'selected';
                                                        endif;
                                                    endforeach;
                                                }
                                            endif;
                                        @endphp
                                        <option {{ $selected }} value="{{ $value->id }}">{{ $value->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.users.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                    <button id="button" type="submit" class="btn btn-md btn-info float-right"
                        style="display: block;">
                        <i class="fas fa-save mr-1"></i>
                        Salvar dados
                    </button>
                    <a id="spinner" class="btn btn-md btn-info float-right text-center" style="display: none;">
                        <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        Atualizando...
                    </a>
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

        const handlePhone = (event) => {
            let input = event.target
            input.value = phoneMask(input.value)
        }

        const phoneMask = (value) => {
            if (!value) return ""
            value = value.replace(/\D/g, '')
            value = value.replace(/(\d{2})(\d)/, "($1) $2")
            value = value.replace(/(\d)(\d{4})$/, "$1-$2")
            return value
        }

    </script>
@stop
