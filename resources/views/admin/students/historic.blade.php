@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h4 class="mb-0">Histórico atividades <strong>{{ $course->title }}</strong></h4>
        <a href="{{ route('admin.student.show', ['id' => $student->id]) }}" class="btn btn-md btn-info"
            title="Adicionar novo registro">
            <i class="fa fa-undo mr-1"></i> Voltar
        </a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert">
            <i class="fa fa-thumbs-up mr-2" aria-hidden="true"></i> {{ session('success') }}
        </div>
    @elseif (session('alert'))
        <div class="alert alert-warning mb-2" role="alert">
            <i class="fa fa-exclamation-triangle mr-2" aria-hidden="true"></i> {{ session('alert') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert">
            <i class="fa fa-thumbs-down mr-2" aria-hidden="true"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $disciplines->count() }}</h3>
                    <p>Disciplinas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $inscriptions->count() }}</h3>
                    <p>Inscrições disciplina(s)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $inscriptions->where('status', 'pago')->count() }}</h3>
                    <p>Inscrições realizadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $inscriptions->where('status', 'pendente')->count() }}</h3>
                    <p>Inscrições pendentes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if (isset($student->image))
                            <img class="profile-user-img img-fluid img-circle" style="width: 120px; height: 120px"
                                src="{{ asset('storage/' . $student->image) }}" alt="User profile picture" />
                        @else
                            <img class="profile-user-img img-fluid img-circle" src="/images/not-photo.jpg"
                                alt="User profile picture" style="width: 120px; height: 120px" />
                        @endif
                    </div>
                    <h3 class="profile-username text-center">{{ $student->name }}</h3>
                    <p class="text-muted text-center">{{ $student->local }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Telefone</b> <a class="float-right">{{ $student->phone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>E-mail</b> <a class="float-right">{{ $student->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Cidade/UF</b> <a class="float-right">{{ $student->city . '/' . $student->state }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Igreja</b> <a class="float-right">{{ $student->church }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Data de entrada</b> <a
                                class="float-right">{{ \Carbon\Carbon::parse($student->date_entry)->format('d/m/Y') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Data de saída</b> <a
                                class="float-right">{{ \Carbon\Carbon::parse($student->exit)->format('d/m/Y') }}</a>
                        </li>
                    </ul>
                    <a href="{{ route('admin.student.edit', ['id' => $student->id]) }}"
                        class="btn btn-primary btn-block"><b>Editar registro</b></a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Disciplinas</h3>
                        <a href="{{ route('admin.students.historic.pdf', ['id' => $course->id, 'std' => $student->id]) }}"
                            class="btn btn-xs btn-default" target="_blank">
                            <i class="fa fa-file-pdf mr-2"></i> Gerar histórico em PDF
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @php
                        $date_now = \Carbon\Carbon::now()->format('Y-m-d');
                    @endphp
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Duração</th>
                                @if ($course->institution == 'setbal')
                                    <th class="text-center">Ano</th>
                                    <th class="text-center">Semestre</th>
                                    <th class="text-center">Créditos</th>
                                    <th class="text-center" title="Carga horária">CH</th>
                                    <th class="text-center">Nota</th>
                                @endif
                                @if ($course->institution == 'ead')
                                    <th class="text-center">1º nota</th>
                                    <th class="text-center">2º nota</th>
                                    <th class="text-center">3º nota</th>
                                    <th class="text-center">Média</th>
                                    <th class="text-center">Histórico</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($disciplines as $discipline)
                                @php
                                    $inscription = App\Http\Controllers\StudentController::verify_inscription($student->id, $discipline->course_id, $discipline->id);
                                    $expiration_date = App\Http\Controllers\StudentController::expiration_date($student->id, $discipline->course_id, $discipline->id);
                                @endphp

                                <tr>
                                    <td>
                                        @if ($inscription)
                                            <strong>{{ $discipline->title }}</strong>
                                        @else
                                            <span class="font-weight-light">{{ $discipline->title }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($inscription)
                                            <p style="line-height: 1; margin-bottom: 0">
                                                @if ($date_now > $expiration_date->closing_date)
                                                    <span class="d-block">Tempo esgotado</span>
                                                    <small>{{ \Carbon\Carbon::parse($expiration_date->date_inscription)->format('d/m/Y') }}
                                                        até
                                                        {{ \Carbon\Carbon::parse($expiration_date->closing_date)->format('d/m/Y') }}</small>
                                                @else
                                                    {{ \Carbon\Carbon::parse($date_now)->floatDiffInDays($expiration_date->closing_date) }}
                                                    dia(s)
                                                    <small
                                                        class="d-block">{{ \Carbon\Carbon::parse($expiration_date->date_inscription)->format('d/m/Y') }}
                                                        até
                                                        {{ \Carbon\Carbon::parse($expiration_date->closing_date)->format('d/m/Y') }}</small>
                                                @endif
                                            </p>
                                        @endif
                                    </td>
                                    @if ($course->institution == 'setbal')
                                        <td class="text-center">{{ $discipline->year }}º</td>
                                        <td class="text-center">{{ $discipline->semester }}º</td>
                                        <td class="text-center">{{ $discipline->credits }}</td>
                                        <td class="text-center">{{ $discipline->workload }}</td>
                                        <td class="text-center">
                                            @if ($inscription)
                                                @php
                                                    $nota = App\Http\Controllers\StudentController::getNote($discipline->course->id, $discipline->id, $student->id);
                                                    if ($nota == 0) {
                                                        echo '<a class="btn btn-sm btn-default" data-toggle="modal" data-target="#modal-default' . $discipline->id . '">' . number_format($nota, 1, '.', '.') . '</a>';
                                                    } elseif ($nota < 6) {
                                                        echo '<a class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-default' . $discipline->id . '">' . number_format($nota, 1, '.', '.') . '</a>';
                                                    } else {
                                                        echo '<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-default' . $discipline->id . '">' . number_format($nota, 1, '.', '.') . '</a>';
                                                    }
                                                @endphp
                                            @else
                                                0.0
                                            @endif
                                        </td>
                                    @endif
                                    @if ($course->institution == 'ead')
                                        <td class="text-center">
                                            @php
                                                $note1 = App\Http\Controllers\DashboardController::getNoteMultiple($discipline->course->id, $discipline->id, $student->id);
                                            @endphp
                                            @if ($note1 == 0)
                                                <s>0.0</s>
                                            @else
                                                @if ($note1 > 6)
                                                    <span class="badge badge-primary p-2">
                                                        {{ number_format($note1, 1, '.', '') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger p-2">
                                                        {{ number_format($note1, 1, '.', '') }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $note2 = App\Http\Controllers\DashboardController::getNoteOpen($discipline->course->id, $discipline->id, $student->id);
                                            @endphp
                                            @if ($note2 == 0)
                                                <s>0.0</s>
                                            @else
                                                @if ($note2 > 6)
                                                    <span class="badge badge-primary p-2">
                                                        {{ number_format($note2, 1, '.', '') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger p-2">
                                                        {{ number_format($note2, 1, '.', '') }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $note3 = App\Http\Controllers\DashboardController::getNoteJob($discipline->course->id, $discipline->id, $student->id);
                                            @endphp
                                            @if ($note3 == 0)
                                                <s>0.0</s>
                                            @else
                                                @if ($note3 > 6)
                                                    <span class="badge badge-primary p-2">
                                                        {{ number_format($note3, 1, '.', '') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger p-2">
                                                        {{ number_format($note3, 1, '.', '') }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                if ($note1 == 0 && $note2 == 0 && $note3 == 0) {
                                                    echo '<s>0.0</s>';
                                                } else {
                                                    if ($note1 != 0 && $note2 != 0 && $note3 != 0) {
                                                        $soma_notas = $note1 + $note2 + $note3;
                                                
                                                        $media = $soma_notas / 3;
                                                        if ($media == 0) {
                                                            echo '0.0';
                                                        } elseif ($media > 0 && $media <= 6.9) {
                                                            echo "<span class='badge badge-danger p-2'>" . number_format($media, 1, '.', '') . '</span>';
                                                        } else {
                                                            echo "<span class='badge badge-primary p-2'>" . number_format($media, 1, '.', '') . '</span>';
                                                        }
                                                    } else {
                                                        echo '...';
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                @if ($inscription)
                                                    <a href="{{ route('admin.student.ead', ['course' => $discipline->course->slug, 'discipline' => $discipline->slug, 'user' => $student->id]) }}"
                                                        class="btn btn-default btn-sm"
                                                        title="Histórico da disciplina do aluno">
                                                        <i class="fa fa-graduation-cap"></i>
                                                    </a>
                                                @else
                                                    <button type="button" class="btn btn-default btn-sm"
                                                        title="Disciplina indisponível">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @foreach ($disciplines as $discipline)
                        <div class="modal fade" id="modal-default{{ $discipline->id }}" aria-hidden="true"
                            style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Nota avaliativa</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ $discipline->title }}</p>
                                        @if (isset($discipline->note->nota))
                                            existe
                                        @else
                                            não existe
                                        @endif
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar
                                            Janela</button>
                                        <form method="POST" action="{{ route('admin.student.historic.store.note') }}">
                                            @csrf
                                            <div class="input-group">
                                                <input type="hidden" name="user_id" value="{{ $student->id }}" />
                                                <input type="hidden" name="course_id" value="{{ $course->id }}" />
                                                <input type="hidden" name="discipline_id"
                                                    value="{{ $discipline->id }}" />
                                                <input type="text" name="nota" placeholder="0.0" required
                                                    class="form-control">
                                                <span class="input-group-append">
                                                    <button type="submit" class="btn btn-primary">Pontuar</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

    </div>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop

@section('js')

@stop
