@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h4 class="mb-0">Disciplinas presenciais <strong>{{ $course->title }}</strong></h4>
        </div>
        <a href="{{ route('admin.students.show', ['id' => $student->id]) }}" class="btn btn-md btn-info"
            title="Listar detalhes do estudante">
            <i class="fa fa-undo mr-1"></i> Voltar
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
        <div class="col-lg-3 col-6">

            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $registrations->count() }}</h3>
                    <p>Curso(s) Matriculado(s)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-graduation-cap"></i>
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
                    <i class="fa fa-edit"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $inscriptions->where('status', 'pendente')->count() }}</h3>
                    <p>Inscrições pendente(s)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $registrations->where('payment', 'nao')->count() }}</h3>
                    <p>Matricula(s) pendente(s)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-down"></i>
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
                                src="{{ asset('storage/' . $student->image) }}" alt="User profile picture">
                        @else
                            <img class="profile-user-img img-fluid img-circle" 
                                 src="https://dummyimage.com/28x28/b6b7ba/fff" 
                                 style="width: 120px; height: 120px" 
                                 alt="User profile picture">
                        @endif
                    </div>
                    <h3 class="profile-username text-center">{{ $student->name }}</h3>
                    <p class="text-muted text-center">{{ $student->local }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Telefone</b> <a class="float-right">{{ $student->phone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>E-mail:</b> <a class="float-right">{{ $student->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Matricula</b> <a class="float-right">{{ $student->registration }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Cidade/UF</b> <a class="float-right">{{ $student->city . '/' . $student->state }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Igreja</b> <a class="float-right">{{ $student->igreja }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Naturalizade</b> <a class="float-right">{{ $student->naturalness }}</a>
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
                    <a href="{{ route('admin.students.edit', ['id' => $student->id]) }}"
                        class="btn btn-primary btn-block"><b>Mais detalhes</b></a>
                </div>

            </div>

        </div>

        <div class="col-md-9">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de disciplinas {{ $course->title }}</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body table-responsive p-0">

                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="py-2">TÍtulo</th>
                                <th class="py-2">Período</th>
                                <th class="py-2 text-center" title="Pagamento da inscrição">Pagamento</th>
                                <th class="py-2 text-center">Ano</th>
                                <th class="py-2 text-center">Semestre</th>
                                <th class="py-2 text-center" title="Créditos">Créd.</th>
                                <th class="py-2 text-center" title="Carga horária">CH</th>
                                <th class="py-2 text-center" title="Nota avaliativa">Nota</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjects->sortBy('semester') as $subject)
                                <tr>
                                    <td class="py-2">
                                        @if ($subject->inscriptions->where('user_id', $student->id)->count())
                                            <strong title="inscrito na disciplina">{{ $subject->title }}</strong>
                                        @else
                                            {{ $subject->title }}
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $diff = App\Http\Controllers\StudentController::date_diff($student->id, $subject->course->id, $subject->id);
                                            $date_now = \Carbon\Carbon::now()->format('Y-m-d');
                                        @endphp
                                        @if ($diff)
                                            <p style="line-height: 1; margin-bottom: 0">
                                                @if ($date_now > $diff->closing_date)
                                                    <span>Tempo esgotado</span>
                                                    <br />
                                                    <small>{{ \Carbon\Carbon::parse($diff->date_inscription)->format('d/m/Y') }}
                                                        até
                                                        {{ \Carbon\Carbon::parse($diff->closing_date)->format('d/m/Y') }}</small>
                                                @else
                                                    {{ \Carbon\Carbon::parse($date_now)->floatDiffInDays($diff->closing_date) }}
                                                    dia(s)
                                                    <br />
                                                    <small>{{ \Carbon\Carbon::parse($diff->date_inscription)->format('d/m/Y') }}
                                                        até
                                                        {{ \Carbon\Carbon::parse($diff->closing_date)->format('d/m/Y') }}</small>
                                                @endif
                                            </p>
                                        @endif
                                    </td>
                                    <td class="py-2 text-center">
                                        @if ($subject->inscriptions->where('user_id', $student->id)->where('status', 'pago')->count())
                                            <span>Confirmado</span>
                                        @else
                                            <small class="badge badge-danger px-3 py-1">Pendente</small>
                                        @endif
                                    </td>
                                    <td class="py-2 text-center"><span
                                            title="{{ $subject->year }}º ano">{{ $subject->year }}º</span></td>
                                    <td class="py-2 text-center"><span
                                            title="{{ $subject->semester }}º semestre">{{ $subject->semester }}º</span>
                                    </td>
                                    <td class="py-2 text-center">{{ $subject->credits }}</td>
                                    <td class="py-2 text-center">{{ $subject->workload }}h</td>
                                    <td class="py-2 text-center">
                                        @forelse ($subject->notes->where('user_id', $student->id)->where('subject_id', $subject->id) as $item)
                                            {{ number_format($item['nota'], 1, '.', '') }}
                                        @empty
                                            <s>0.0</s>
                                        @endforelse
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if ($subject->inscriptions->where('user_id', $student->id)->where('status', 'pago')->count())
                                                <a href="" class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#modal-sm{{ $subject->id }}"
                                                    title="Adionar nota do aluno a disciplina">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-default btn-sm"
                                                    title="Indisponível: o aluno não realizou a inscrição">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @foreach ($subjects->sortBy('semester') as $subject)
                        <div class="modal fade" id="modal-sm{{ $subject->id }}" style="display: none;"
                            aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <form method="POST" action="{{ route('admin.students.store.note') }}">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Adicionar Nota</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <span>Aluno(a): {{ $student->name }}</span><br />
                                            <span>Disciplina: {{ $subject->title }}</span>
                                            <br /><br />
                                            <input type="hidden" name="user_id" value="{{ $student->id }}" />
                                            <input type="hidden" name="course_id" value="{{ $course->id }}" />
                                            <input type="hidden" name="subject_id" value="{{ $subject->id }}" />

                                            @forelse ($subject->notes->where('user_id', $student->id)->where('subject_id', $subject->id) as $item)
                                                <input type="text" name="nota"
                                                    value="{{ number_format($item['nota'], 1, '.', '') }}"
                                                    class="form-control" required placeholder="0.0" maxlength="4" />
                                            @empty
                                                <input type="text" name="nota" class="form-control" required
                                                    placeholder="0.0" maxlength="4" />
                                            @endforelse

                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Fechar</button>
                                            <button type="submit" class="btn btn-info">
                                                <i class="fa fa-save"></i> Salvar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

    </div>

@stop

@section('css')
@stop

@section('js')

@stop
