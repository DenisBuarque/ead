@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Disciplina EAD</h1>
        <a href="{{ route('admin.disciplines.index') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
            <i class="fa fa-list mr-1"></i> Listar disciplinas
        </a>
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
            <h3 class="card-title">{{ $discipline->title }}</h3>
            <div class="card-tools">

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Modulos</span>
                                    <span
                                        class="info-box-number text-center text-muted mb-0">{{ $modules->where('category', 'module')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Arquivos</span>
                                    <span
                                        class="info-box-number text-center text-muted mb-0">{{ $modules->where('category', 'file')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Vídeos</span>
                                    <span
                                        class="info-box-number text-center text-muted mb-0">{{ $modules->where('category', 'movie')->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">

                            <a href="{{ route('admin.module.create', ['id' => $discipline->id]) }}"
                                class="btn btn-md btn-primary mb-2"
                                title="Clique aqui para adicionar um novo módule de ensino">
                                <i class="fa fa-plus mr-2"></i> Adicionar novo módulo
                            </a>

                            <div class="card card-defaul">
                                <div class="card-header">
                                    <h3 class="card-title">Modulos de ensino</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Criado</th>
                                                <th>Atualizado</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($modules->where('category','module') as $value)
                                                <tr>
                                                    <td>{{ $value->title }}</td>
                                                    <td class="py-2">
                                                        {{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:m:s') }}
                                                    </td>
                                                    <td class="py-2">
                                                        {{ \Carbon\Carbon::parse($value->updated_at)->format('d/m/Y H:m:s') }}
                                                    </td>
                                                    <td class="text-right py-0 align-middle">
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('admin.module.edit', ['id' => $value->id]) }}"
                                                                class="btn btn-default"><i class="fas fa-edit"></i></a>
                                                            <a href="{{ route('admin.module.destroy', ['id' => $value->id]) }}"
                                                                onclick="return confirmaExcluir()"
                                                                class="btn btn-default"><i class="fas fa-trash"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">Nenhum módulo de ensino cadastrado</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="timeline">
                                <div class="time-label">
                                    <a href="{{ route('admin.module.create.movie', ['id' => $discipline->id]) }}"
                                        class="btn btn-md btn-primary mb-2"
                                        title="Clique aqui para adicionar um novo módule de ensino">
                                        <i class="fa fa-plus mr-2"></i> Adicionar novo vídeo
                                    </a>
                                </div>

                                @foreach ($modules->where('category', 'movie') as $movie)
                                    <div>
                                        <i class="fas fa-video bg-maroon"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($movie->created_at)->format('d/m/Y H:m:s') }}</span>
                                            <h3 class="timeline-header"><label>{{ $movie->title }}</label></h3>
                                            <div class="timeline-body">
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item"
                                                        src="https://www.youtube.com/embed/{{ $movie->movie }}"
                                                        allowfullscreen=""></iframe>
                                                </div>
                                            </div>
                                            <div class="timeline-footer">
                                                <div class="d-flex justify-content-between mb-3">
                                                    <a href="{{ route('admin.module.edit', ['id' => $movie->id]) }}"
                                                        class="btn btn-sm bg-info"><i class="far fa-fw fa-edit"></i> Editar
                                                        vídeo</a>

                                                    <a href="{{ route('admin.module.destroy', ['id' => $movie->id]) }}"
                                                        onclick="return confirmaExcluir()" class="btn btn-sm bg-maroon"><i
                                                            class="fa fa-trash"></i> Excluir
                                                        vídeo</a>
                                                </div>

                                                <span>{{ $movie->description }}</span>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div>
                                    <i class="fas fa-clock bg-gray"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">

                    <div class="card card-widget widget-user-2 shadow-sm">

                        <div class="widget-user-header bg-info">
                            <div class="widget-user-image">
                                @if (isset($discipline->user->image))
                                    <img class="img-circle" src="{{ asset('storage/' . $discipline->user->image) }}"
                                        alt="Photo" style="width: 60px; height: 60px;" />
                                @else
                                    <img class="img-circle" src="/images/not-photo.jpg" alt="Photo"
                                        style="width: 60px; height: 60px;" />
                                @endif
                            </div>
                            <h3 class="widget-user-username">{{ $discipline->user->name }}</h3>
                            <h5 class="widget-user-desc">Professor(a)</h5>
                        </div>
                        <div class="card-footer p-0">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <span class="nav-link">
                                        Disciplina <span class="float-right">{{ $discipline->title }}</span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                        Ano <span class="float-right">{{ $discipline->year }}º</span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                        Semestre <span class="float-right">{{ $discipline->semester }}º</span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                        Periodo <span class="float-right">{{ $discipline->period }}º</span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                        Carga horária <span class="float-right">{{ $discipline->workload }}</span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                        Créditos <span class="float-right">{{ $discipline->credits }}</span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                        Teste avaliativo <span class="float-right">{{ $discipline->quiz }}</span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                        Visivel do aluno <span class="float-right">{{ $discipline->status }}</span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                        Criado <span
                                            class="float-right">{{ \Carbon\Carbon::parse($discipline->created_at)->format('d/m/Y H:m:s') }}</span>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">
                                        Alterado <span
                                            class="float-right">{{ \Carbon\Carbon::parse($discipline->updated_at)->format('d/m/Y H:m:s') }}</span>
                                    </span>
                                </li>

                            </ul>
                        </div>
                    </div>

                    {!! $discipline->description !!}


                    <div class="card mt-3">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Arquivos:</h3>
                                <a href="{{ route('admin.module.create.file', ['id' => $discipline->id]) }}"
                                    class="btn btn-sm btn-primary">Adicionar Arquivo</a>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th style="width: 40px">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($modules->where('category', 'file') as $module)
                                        <tr>
                                            <td>
                                                <p style="line-height: 1; margin-bottom: 0">
                                                    <label>{{ $module->title }}</label>
                                                    <br />
                                                    <small>{{ $module->description }}</small>
                                                </p>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ Storage::url($module->file) }}" target="_blank"
                                                        class="btn btn-default btn-sm" title="Excluir registro">
                                                        <i class="far fa-fw fa-file-pdf"></i>
                                                    </a>
                                                    <a href="{{ route('admin.module.edit', ['id' => $module->id]) }}"
                                                        class="btn btn-default btn-sm" title="Alterar registro">
                                                        <i class="far fa-fw fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.module.destroy', ['id' => $module->id]) }}"
                                                        onclick="return confirmaExcluir()" class="btn btn-default btn-sm"
                                                        title="Excluir registro">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>


                </div>
            </div>
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
