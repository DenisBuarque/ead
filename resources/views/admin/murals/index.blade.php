@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.murals.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <select name="discipline" class="form-control mr-1">
                        <option value="">Disciplina</option>
                        @foreach ($disciplines as $discipline)
                            @if ($search_discipline == $discipline->id)
                                <option value="{{ $discipline->id }}" selected>{{ $discipline->title }}
                                    {{ $discipline->type }}
                                </option>
                            @else
                                <option value="{{ $discipline->id }}">{{ $discipline->title }} {{ $discipline->type }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <input type="text" name="title" value="{{ $search_title }}" id="title"
                        class="form-control mr-1" placeholder="Título" />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        @can('mural-create')
            <a href="{{ route('admin.mural.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
                <i class="fa fa-plus mr-1"></i> Adicionar novo
            </a>
        @endcan
    </div>
@stop

@section('content')

    @if (session('success'))
        <script>
            setTimeout(() => {
                document.getElementById("message").style.display = "none";
            }, 4000);
        </script>
        <div id="message" class="alert alert-success mb-2" role="alert">
            <i class="fa fa-thumbs-up mr-2" aria-hidden="true"></i> {{ session('success') }}
        </div>
    @elseif (session('alert'))
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true"></i> {{ session('alert') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="fa fa-thumbs-down mr-2" aria-hidden="true"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de mural de recados cadastrados</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Duração</th>
                        <th class="py-2">Título</th>
                        <th class="py-2">Disciplina</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($murals as $mural)
                        <tr>
                            <td class="py-2">{{ \Carbon\Carbon::parse($mural->date)->format('d/m/Y') }}</td>
                            <td class="py-2">{{ $mural->title }}</td>
                            <td class="py-2">
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $mural->discipline->title }}
                                    <br /><small>{{ $mural->course->title }}</small>
                                </p>
                            </td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($mural->created_at)->format('d/m/Y H:m:s') }}</td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($mural->updated_at)->format('d/m/Y H:m:s') }}</td>
                            <td>
                                <div class="btn-group">
                                    @can('mural-edit')
                                        <a href="{{ route('admin.mural.edit', ['id' => $mural->id]) }}"
                                            class="btn btn-default btn-sm" title="Editar registro">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('mural-delete')
                                        <a href="{{ route('admin.mural.destroy', ['id' => $mural->id]) }}"
                                            onclick="return confirmaExcluir()" class="btn btn-default btn-sm"
                                            title="Excluir registro">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-2 text-center" colspan="6">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($murals)
                <div class="mt-2 mx-2">
                    {{ $murals->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>

@stop

@section('css')

@stop

@section('js')
    <script>
        function confirmaExcluir() {
            var conf = confirm(
                "Deseja mesmo excluir o registro? Os dados serão perdidos.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }
    </script>
@stop
