@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.disciplines.index') }}">
                <div class="input-group input-group-md">
                    <input type="text" name="title" value="{{ $search_title }}" id="search" class="form-control mr-1"
                        placeholder="Título" />
                    <select name="course" class="form-control mr-1">
                        <option value="">Curso</option>
                        @foreach ($courses as $course)
                            @if ($search_course == $course->id)
                                <option value="{{ $course->id }}" selected>{{ $course->title }}
                                    ({{ $course->institution }})
                                </option>
                            @else
                                <option value="{{ $course->id }}">{{ $course->title }} ({{ $course->institution }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <select name="institution" class="form-control mr-1">
                        <option value="">Instituição</option>
                        <option value="setbal" @if ($search_institution == 'setbal') selected @endif>Setbal</option>
                        <option value="ead" @if ($search_institution == 'ead') selected @endif>EAD</option>
                    </select>
                    <select name="status" class="form-control mr-1">
                        <option value="">Status</option>
                        <option value="active" @if ($search_status == 'active') selected @endif>Ativo</option>
                        <option value="inactive" @if ($search_status == 'inactive') selected @endif>Inativo</option>
                    </select>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        @can('discipline-create')
            <a href="{{ route('admin.discipline.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $total->count() }}</h3>
                    <p>Disciplinas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $disciplines->where('quiz', 'active')->count() }}</h3>
                    <p>Testes avaliativos liberados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-question"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $disciplines->where('status', 'active')->count() }}</h3>
                    <p>Disciplina(s) Liberada(s)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $disciplines->where('status', 'inactive')->count() }}</h3>
                    <p>Disciplina(s) Indisponível</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-down"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de disciplinas cadastradas no sistema</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Título</th>
                        <th class="py-2 text-center">Ano</th>
                        <th class="py-2 text-center">Semestre</th>
                        <th class="py-2">Curso</th>
                        <th class="py-2 text-center">Avaliação</th>
                        <th class="py-2 text-center">Status</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($disciplines as $discipline)
                        <tr>
                            <td class="py-2">
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $discipline->title }}<br />
                                    @if ($discipline->institution == 'setbal')
                                        <small>Setbal</small>
                                    @else
                                        <small>EAD</small>
                                    @endif
                                </p>
                            </td>
                            <td class="py-2 text-center">{{ $discipline->year }}º ano</td>
                            <td class="py-2 text-center">{{ $discipline->semester }}º Semestre</td>
                            <td class="py-2">
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $discipline->course->title }}
                                    <br /><small>{{ $discipline->course->polo->title }}</small>
                                </p>
                            </td>
                            <td class="py-2 text-center">
                                @if ($discipline->quiz == 'active')
                                    <small class="badge badge-success px-3">Liberado</small>
                                @else
                                    <small class="badge badge-danger px-2">Bloqueado</small>
                                @endif
                            </td>
                            <td class="py-2 text-center">
                                @if ($discipline->status == 'active')
                                    <small class="badge badge-warning px-4">Ativo</small>
                                @else
                                    <small class="badge badge-danger px-3">Inativo</small>
                                @endif
                            </td>
                            <td class="py-2 text-center">
                                <div class="btn-group">

                                    @if ($discipline->institution == 'ead')
                                        @can('discipline-module')
                                            <a href="{{ route('admin.discipline.show', ['id' => $discipline->id]) }}"
                                                class="btn btn-default btn-sm" title="Modulos da disciplina">
                                                <i class="fa fa-sitemap"></i>
                                                <span
                                                    style="position: absolute; top: -3px; left: -5px; width: 12px; height: 14px; border-radius: 3px; background-color: #FF8c00; color: #FFFFFF; padding: 0; font-size: 9px;">{{ $discipline->discipline_modules->count() }}</span>
                                            </a>
                                        @endcan
                                    @endif
                                    @can('discipline-edit')
                                        <a href="{{ route('admin.discipline.edit', ['id' => $discipline->id]) }}"
                                            class="btn btn-default btn-sm" title="Editar registro">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('discipline-delete')
                                        <a href="{{ route('admin.discipline.destroy', ['id' => $discipline->id]) }}"
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
                            <td class="py-2 text-center" colspan="7">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($disciplines)
                <div class="mt-2 mx-2">
                    {{ $disciplines->withQueryString()->links('pagination::bootstrap-5') }}
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
