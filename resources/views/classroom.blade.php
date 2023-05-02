@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1 class="m-0">Sala de Aula <strong>{{ $course->title }}</strong></h1>
        <a href="{{ route('dashboard') }}" class="btn btn-md btn-danger" title="Sair da sala de aula.">
            <i class="fa fa-times mr-1"></i> Sair da Sala
        </a>
    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fa fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de disciplinas</span>
                    <span class="info-box-number">{{ $disciplines->where('course_id', $course->id)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="far fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Minhas inscrições</span>
                    <span class="info-box-number">
                        {{ App\Http\Controllers\DashboardController::inscriptions_student($course->id) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fa fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Inscrições Pendentes</span>
                    <span class="info-box-number">{{ $requestinscriptions->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <a href="{{ route('classroom.customer.service') }}" title="Clique aqui para listar seus atendimentos.">
                <div class="info-box mb-3 bg-info">
                    <span class="info-box-icon"><i class="fa fa-comments"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Atendimentos abertos</span>
                        <span class="info-box-number">
                            {{ $customerservices }}
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="fa fa-thumbs-up mr-2" aria-hidden="true"></i> {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="fa fa-thumbs-down mr-2" aria-hidden="true"></i> {{ session('error') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de disciplinas do curso</h3>
                    <div class="card-tools">
                        <a href="{{ route('dashboard.classroom.historic.pdf', ['id' => $course->id]) }}"
                            class="btn btn-xs btn-warning" target="_blank">
                            <i class="fa fa-file-pdf mr-2"></i> Gerar histórico em PDF
                        </a>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    @php
                        $date_now = \Carbon\Carbon::now()->format('Y-m-d');
                    @endphp
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Acesso</th>
                                @if ($course->institution == 'setbal')
                                    <th class="text-center">Ano</th>
                                    <th class="text-center">Semestre</th>
                                    <th class="text-center">Créditos</th>
                                    <th class="text-center" title="Carga horária">CH</th>
                                    <th class="text-center">Nota</th>
                                @endif
                                @if ($course->institution == 'ead')
                                    <th class="text-center" title="Nota questões multiplas">1º nota</th>
                                    <th class="text-center" title="Nota questões abertas">2º nota</th>
                                    <th class="text-center" title="Nota do trabalho acadêmico">3º nota</th>
                                    <th class="text-center">Média</th>
                                    <th class="text-center" style="width: 40px">Ações</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($disciplines->sortBy('year') as $discipline)
                                @php
                                    $inscription = App\Http\Controllers\DashboardController::verify_inscription($discipline->course_id, $discipline->id);
                                    $expiration_date = App\Http\Controllers\DashboardController::expiration_date($discipline->course_id, $discipline->id);
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
                                        <td class="text-center">{{ number_format(App\Http\Controllers\StudentController::getNote($discipline->course_id, $discipline->id, auth()->user()->id),1,'.','.') }}</td>
                                    @endif
                                    @if ($course->institution == 'ead')
                                        <td class="text-center">
                                            @php
                                                $note1 = App\Http\Controllers\DashboardController::getNoteMultiple($discipline->course->id, $discipline->id, auth()->user()->id);
                                            @endphp
                                            @if ($note1 == 0)
                                                <s>0.0</s>
                                            @else
                                                <a title="Clique para ver o resulta de sua avaliação."
                                                    href="{{ route('classroom.multiplequestion.result', ['course' => $discipline->course->slug, 'discipline' => $discipline->slug]) }}">
                                                    {{ number_format($note1, 1, '.', '') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $note2 = App\Http\Controllers\DashboardController::getNoteOpen($discipline->course->id, $discipline->id, auth()->user()->id);
                                            @endphp
                                            @if ($note2 == 0)
                                                <s>0.0</s>
                                            @else
                                                {{ number_format($note2, 1, '.', '') }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $note3 = App\Http\Controllers\DashboardController::getNoteJob($discipline->course->id, $discipline->id, auth()->user()->id);
                                            @endphp
                                            @if ($note3 == 0)
                                                <s>0.0</s>
                                            @else
                                                {{ number_format($note3, 1, '.', '') }}
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
                                                            echo "<button type='button' class='btn btn-danger btn-xs' title='Reprovado'>" . number_format($media, 1, '.', '') . '</button>';
                                                        } else {
                                                            echo number_format($media, 1, '.', '');
                                                        }
                                                    } else {
                                                        echo '...';
                                                    }
                                                }
                                                
                                            @endphp
                                        </td>
                                        <td class="text-center">
                                            @if ($inscription)
                                                <div class="btn-group">
                                                    <a href="{{ route('livingroom', ['course' => $course->slug, 'discipline' => $discipline->slug]) }}"
                                                        class="btn btn-default btn-sm"
                                                        title="Clique para acessar os modulos de estudo da disciplina.">
                                                        <i class="fa fa-sitemap"></i>
                                                        <span
                                                            style="position: absolute; top: -7px; left: 2px; width: 12px; height: 14px; border-radius: 3px; background-color: #0bb833; color: #FFFFFF; padding: 0; font-size: 9px;">
                                                            {{ $discipline->discipline_modules->count() }}
                                                        </span>
                                                    </a>
                                                    @if ($discipline->forums->count() > 0)
                                                        <a href="{{ route('livingroom.discipline.forum', ['course' => $course->slug, 'discipline' => $discipline->slug]) }}"
                                                            class="btn btn-default btn-sm"
                                                            title="Clique para participar do forum de discursões.">
                                                            <i class="fas fa-bullhorn"></i>
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-default btn-sm"
                                                            title="Forum de discursões indisponível">
                                                            <i class="fa fa-ban"></i>
                                                        </button>
                                                    @endif
                                                    @if ($discipline->quiz == 'active')
                                                        <a href="{{ route('dashboard.evaluation', ['course' => $discipline->course->slug, 'discipline' => $discipline->slug]) }}"
                                                            class="btn btn-default btn-sm"
                                                            title="Clique para realizar os testes avaliativos de aprendisagem.">
                                                            <i class="fas fa-hourglass-half"></i>
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-default btn-sm"
                                                            title="Testes avaliativos indisponível">
                                                            <i class="fa fa-ban"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm"
                                                        title="Modulos de ensino indisponível">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-sm"
                                                        title="Forum de discursões indisponível">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-sm"
                                                        title="Testes avaliativos indisponível">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                </div>
                                            @endif

                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Nenhuma disciplina disponível para estudo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <div class="col-md-3">

            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Inscrições solicitadas</h3>
                    <div class="card-tools">
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <tbody>
                                @forelse ($requestinscriptions as $request)
                                    <tr>
                                        <td>
                                            <p style="line-height: 1; margin-bottom: 0">
                                                <small class="text-danger"><b>Solicitação em análise</b></small><br />
                                                <strong>{{ $request->discipline->title }}</strong><br />
                                                <small>
                                                    {{ $request->course->title }}<br />
                                                    {{ $request->user->name }}<br />
                                                    {{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y H:m:s') }}
                                                </small>
                                            </p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">Nunhuma pré inscrição solicitada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop

@section('css')

@stop

@section('js')
    <script></script>
@stop
