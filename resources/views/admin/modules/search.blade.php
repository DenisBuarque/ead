@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <form method="POST" action="{{ route('admin.modules.search') }}">
            @csrf
            <div class="input-group input-group-md">
                <select name="subject_id" class="form-control" required>
                    <option value="">Disciplinas</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->title }}</option>
                    @endforeach
                </select>
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">
                        <i class="fa fa-search mr-1"></i> Buscar
                    </button>
                </span>
            </div>
        </form>
        <div>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
                <i class="fa fa-table mr-1"></i> Listar disciplinas
            </a>
            <a href="{{ route('admin.modules.create',['id' => $disciplina->id]) }}" class="btn btn-md btn-primary" title="Adicionar novo registro">
                <i class="fa fa-plus mr-1"></i> Adicionar Modulo
            </a>
        </div>
        
    </div>
@stop

@section('content')

    @if (session('success'))
        <div id="message" class="alert alert-success mb-2" role="alert">
            <i class="fa fa-thumbs-up mr-2" aria-hidden="true"></i> {{ session('success') }}
        </div>
    @elseif (session('alert'))
        <div id="message" class="alert alert-warning mb-2" role="alert">
            <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true"></i> {{ session('alert') }}
        </div>
    @elseif (session('error'))
        <div id="message" class="alert alert-danger mb-2" role="alert">
            <i class="fa fa-thumbs-down mr-2" aria-hidden="true"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $disciplina->title }}</h3>
                    <p class="text-muted text-center">Disciplina</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Modulos:</b> <a class="float-right">{{ count($modules) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Total de alunos:</b> <a class="float-right">0</a>
                        </li>
                        <li class="list-group-item">
                            <b>Curso</b> <a class="float-right">{{ $disciplina->course->title }}</a>
                        </li>
                    </ul>
                    <a href="{{ route('admin.modules.create',['id' => $disciplina->id]) }}" class="btn btn-primary btn-block"><b>Adicionar Modulo</b></a>
                </div>

            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de modulos {{ $disciplina->title }}</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="py-2">Título do modulo</th>
                                <th class="py-2 text-center" style="width: 100px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($modules as $module)
                                <tr>
                                    <td class="py-2">{{ $module->title }}</td>
                                    <td class="py-2 text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.modules.edit', ['id' => $module->id]) }}"
                                                class="btn btn-default btn-sm" title="Editar registro">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.modules.destroy', ['id' => $module->id, 'idsub' => $module->subject_id]) }}"
                                                onclick="return confirmaExcluir()" class="btn btn-default btn-sm"
                                                title="Excluir registro">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td class="py-2 text-center" colspan="3">
                                        <span>Nenhum registro cadastrado.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>




@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop

@section('js')
    <script>
        function confirmaExcluir() {
            var conf = confirm(
                "Deseja mesmo excluir o registro? Os dados serão perdidos e não poderão ser recuperados novamente.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }

        setTimeout(() => {
            document.getElementById("message").style.display = "none";
        }, 5000);
    </script>
@stop
