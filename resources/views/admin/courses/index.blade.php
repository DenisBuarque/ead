@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.courses.index') }}">
                <div class="input-group input-group-md">
                    <input type="text" name="title" value="{{ $search_title }}" class="form-control mr-1"
                        placeholder="Título" />
                    <select name="polo" class="form-control mr-1">
                        <option value="">Polo</option>
                        @foreach ($polos as $polo)
                            @if ($search_polo == $polo->id)
                                <option value="{{ $polo->id }}" selected>{{ $polo->title }}</option>
                            @else
                                <option value="{{ $polo->id }}">{{ $polo->title }}</option>
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
        @can('course-create')
            <a href="{{ route('admin.course.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
                    <h3>{{ $courses->where('institution', 'setbal')->count() }}</h3>
                    <p>Cursos Setbal</p>
                </div>
                <div class="icon">
                    <i class="fa fa-graduation-cap"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $courses->where('institution', 'ead')->count() }}</h3>
                    <p>Cursos EAD</p>
                </div>
                <div class="icon">
                    <i class="fa fa-laptop"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $courses->where('status', 'active')->count() }}</h3>
                    <p>Cursos ativo(s)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-up"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $courses->where('status', 'inactive')->count() }}</h3>
                    <p>Cursos inativo(s)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-down"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de cursos cadastrados no sistema:</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Título</th>
                        <th class="py-2 text-center">Instituição</th>
                        <th class="py-2">Duração</th>
                        <th class="py-2 text-center">Disciplinas</th>
                        <th class="py-2 text-center">Alunos</th>
                        <th class="py-2 text-center">Status</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2 text-center" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($course->image))
                                        <img src="{{ asset('storage/' . $course->image) }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="{{ asset('images/not-photo.jpg') }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @endif
                                    <p style="line-height: 1; margin-bottom: 0">
                                        {{ $course->title }}
                                        <br /><small>{{ $course->polo->title }}</small>
                                    </p>
                                </div>
                            </td>
                            <td class="py-2 text-center">
                                @if ($course->institution == 'setbal')
                                    <small class="badge badge-info px-3">Setbal</small>
                                @else
                                    <small class="badge badge-success px-4">EAD</small>
                                @endif
                            </td>
                            <td class="py-2">{{ $course->duration }}</td>
                            <td class="py-2 text-center">0</td>
                            <td class="py-2 text-center">0</td>
                            <td class="py-2 text-center">
                                @if ($course->status == 'active')
                                    <small class="badge badge-warning px-2">Ativo</small>
                                @else
                                    <small class="badge badge-danger px-2">Inativo</small>
                                @endif
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($course->created_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($course->updated_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    @can('course-edit')
                                        <a href="{{ route('admin.course.edit', ['id' => $course->id]) }}"
                                            class="btn btn-default btn-sm" title="Editar registro">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('course-delete')
                                        <a href="{{ route('admin.course.destroy', ['id' => $course->id]) }}"
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
                            <td class="py-2 text-center" colspan="9">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($courses)
                <div class="mt-2 mx-2">
                    {{ $courses->withQueryString()->links('pagination::bootstrap-5') }}
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
