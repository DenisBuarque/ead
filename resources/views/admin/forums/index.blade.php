@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.forums.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <select name="course" id="course" class="form-control mr-1">
                        <option value="">Cursos</option>
                        @foreach ($courses as $course)
                            @if ($search_course == $course->id)
                                <option value="{{ $course->id }}" selected>{{ $course->title }}
                                    ({{ $course->institution }})
                                </option>
                            @else
                                <option value="{{ $course->id }}">{{ $course->title }}
                                    ({{ $course->institution }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <select name="discipline" id="discipline" class="form-control mr-1">
                        @if (isset($search_discipline))
                            @foreach ($disciplines as $discipline)
                                @if ($search_discipline == $discipline->id)
                                    <option value="{{ $discipline->id }}" selected>{{ $discipline->title }}
                                        ({{ $discipline->institution }})
                                    </option>
                                @else
                                    <option value="{{ $discipline->id }}">{{ $discipline->title }}
                                        ({{ $discipline->institution }})
                                    </option>
                                @endif
                            @endforeach
                        @endif
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
        @can('forum-create')
            <a href="{{ route('admin.forum.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
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
            <h3 class="card-title">Lista de forum de participação</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th style="width: 30%"> Membros </th>
                        <th>Participantes</th>
                        <th style="width: 20%"></th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($forums as $forum)
                        <tr>
                            <td>
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $forum->title }}<br />
                                    <small class="text-bold">{{ $forum->course->title }} /
                                        {{ $forum->discipline->title }}</small>
                                </p>
                            </td>
                            <td>
                                <ul class="list-inline">
                                    @foreach ($inscriptions as $inscription)
                                        @if ($inscription->discipline_id == $forum->discipline_id)
                                            <li class="list-inline-item">
                                                @if (isset($inscription->user->image))
                                                    <img src="{{ asset('storage/' . $inscription->user->image) }}"
                                                        title="{{ $inscription->user->name }}" alt="Avatar"
                                                        class="table-avatar"
                                                        style="width: 35px; height: 35px; margin-bottom: 2px;" />
                                                @else
                                                    <img src="/images/not-photo.jpg" title="{{ $inscription->user->name }}"
                                                        alt="Avatar" class="table-avatar"
                                                        style="width: 35px; height: 35px; margin-bottom: 2px;" />
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                            <td class="project_progress">
                                @php
                                    $total = $inscriptions->where('discipline_id', $forum->discipline_id)->count();
                                @endphp
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-green" role="progressbar"
                                        aria-valuenow="{{ $total }}" aria-valuemin="0" aria-valuemax="100"
                                        style="width: {{ $total }}%">
                                    </div>
                                </div>
                                <small>
                                    {{ $total }}% alunos
                                </small>
                            </td>
                            <td class="project-actions text-right">
                                @can('forum-menagement')
                                    <a class="btn btn-primary btn-sm"
                                        href="{{ route('admin.forum.show', ['id' => $forum->id]) }}">
                                        <i class="fas fa-folder"></i>
                                    </a>
                                @endcan
                                @can('forum-edit')
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('admin.forum.edit', ['id' => $forum->id]) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endcan
                                @can('forum-delete')
                                    <a class="btn btn-danger btn-sm" onclick="return confirmaExcluir()"
                                        href="{{ route('admin.forum.destroy', ['id' => $forum->id]) }}">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($forums)
                <div class="mt-2 mx-2">
                    {{ $forums->withQueryString()->links('pagination::bootstrap-5') }}
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

        $('#course').change(function() {

            $("#discipline option").remove();

            var course_id = $(this).val();

            if (course_id !== "") {
                $.ajax({
                    type: 'GET',
                    url: "/admin/forums/list/" + course_id,
                    dataType: 'html',
                    success: function(response) {
                        $("#discipline").append(response);
                    }
                })
            }
        });
    </script>
@stop
