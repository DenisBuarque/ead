@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <form method="POST" action="{{ route('admin.modules.search') }}">
            @csrf
            <div class="input-group input-group-md">
                <select name="subject_id" class="form-control mr-1" required>
                    <option value="">Disciplinas</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->title }} ({{ $subject->type }})</option>
                    @endforeach
                </select>
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">
                        <i class="fa fa-search mr-1"></i> Buscar
                    </button>
                </span>
            </div>
        </form>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-md btn-info" title="Voltar a lista de disciplinas">
            <i class="fa fa-undo mr-1"></i> Voltar as disciplinas
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

    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $disciplina->title }}</h3>
                    <p class="text-muted text-center">Disciplina</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Curso</b> <a class="float-right">{{ $disciplina->course->title }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Modulos:</b> <a class="float-right">{{ count($modules) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Arquivo PDF:</b> <a class="float-right">{{ count($files) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Links de vídeos:</b> <a class="float-right">{{ count($videos) }}</a>
                        </li>
                    </ul>
                    <a href="{{ route('admin.modules.create', ['id' => $disciplina->id]) }}"
                        class="btn btn-primary btn-block"><b>Adicionar Modulo</b></a>
                    <a href="{{ route('admin.modules.fileCreate', ['id' => $disciplina->id]) }}"
                        class="btn btn-success btn-block"><b>Adicionar Arquivo PDF</b></a>
                    <a href="{{ route('admin.modules.videoCreate', ['id' => $disciplina->id]) }}"
                        class="btn btn-info btn-block"><b>Adicionar Vídeo</b></a>
                </div>

            </div>
        </div>
        <div class="col-md-9">

            <div class="row">
                @forelse ($videos as $video)
                    <div class="col-md-4">

                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="{{ $video->link }}"
                                allowfullscreen="false"></iframe>
                        </div>
                        {{ $video->description }}
                    </div>
                @empty
                    <div class="col-md-12 text-center">
                        <p>Nenhum vídeo adicionado.</p>
                    </div>
                @endforelse
            </div>


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
                                <th class="py-2">Título</th>
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
                                            <a href="{{ route('admin.modules.destroy', ['id' => $module->id]) }}"
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de arquivos {{ $disciplina->title }}</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="py-2">Título</th>
                                <th class="py-2 text-center" style="width: 100px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($files as $file)
                                <tr>
                                    <td class="py-2">
                                        <p style="line-height: 1; margin-bottom: 0">
                                            {{ $file->title }}<br />
                                            <small>{{ $file->description }}</small>
                                        </p>
                                    </td>
                                    <td class="py-2 text-center">
                                        <div class="btn-group">
                                            <a href="{{ Storage::url($file->file) }}" target="_blank"
                                                class="btn btn-default btn-sm" title="Baixar arquivo">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <a href="{{ route('admin.modules.fileEdit', ['id' => $file->id]) }}"
                                                class="btn btn-default btn-sm" title="Editar registro">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.modules.destroy', ['id' => $file->id]) }}"
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
