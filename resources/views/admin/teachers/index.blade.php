@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.teachers.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <input type="text" name="name" value="{{ $search_name ?? '' }}" id="search"
                        class="form-control mr-1" placeholder="Nome" />
                    <select name="institution" class="form-control mr-1">
                        <option value="">Instituição</option>
                        <option value="setbal" @if ($search_institution == 'setbal') selected @endif>Setbal</option>
                        <option value="ead" @if ($search_institution == 'ead') selected @endif>EAD</option>
                    </select>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        @can('teacher-create')
            <a href="{{ route('admin.teacher.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
                    <h3>{{ $teachers->where('institution', 'setbal')->count() }}</h3>
                    <p>Professores Setbal</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $teachers->where('institution', 'ead')->count() }}</h3>
                    <p>Professores EAD</p>
                </div>
                <div class="icon">
                    <i class="fas fa-laptop"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $teachers->where('access', 'active')->count() }}</h3>
                    <p>Professores ativos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $teachers->where('access', 'inactive')->count() }}</h3>
                    <p>Professores inativos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-down"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de professores cadastrados</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Nome</th>
                        <th class="py-2 text-center">Instituição</th>
                        <th class="py-2">Acesso</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($teacher->image))
                                        <img src="{{ asset('storage/' . $teacher->image) }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="/images/not-photo.jpg" alt="Photo" style="width: 32px; height: 32px;"
                                            class="img-circle img-size-32 mr-2">
                                    @endif
                                    <p style="line-height: 1; margin-bottom: 0">
                                        {{ $teacher->name }}
                                        <br /><small>{{ $teacher->phone }} {{ $teacher->email }}</small>
                                    </p>
                                </div>
                            </td>
                            <td class="py-2 text-center">
                                @if ($teacher->institution == 'setbal')
                                    <small class="badge badge-info px-2">Setbal</small>
                                @else
                                    <small class="badge badge-success px-3">EAD</small>
                                @endif
                            </td>
                            <td class="py-2">
                                @if ($teacher->access == 'active')
                                    <small class="badge badge-warning px-2">Liberado</small>
                                @else
                                    <small class="badge badge-danger px-1">Bloqueado</small>
                                @endif
                            </td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($teacher->created_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($teacher->updated_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    @can('teacher-edit')
                                        <a href="{{ route('admin.teacher.edit', ['id' => $teacher->id]) }}"
                                            class="btn btn-default btn-sm" title="Editar registro">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('teacher-delete')
                                        <a href="{{ route('admin.teacher.destroy', ['id' => $teacher->id]) }}"
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

            @if ($teachers)
                <div class="mt-2 mx-2">
                    {{ $teachers->withQueryString()->links('pagination::bootstrap-5') }}
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
