@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h4 class="mb-0">Informações sobre o estudante</h4>
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn btn-md btn-info" title="Listar registro de alunos">
            <i class="fa fa-users mr-1"></i> Listar alunos
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
                    <p>Curso(s) Matrículado(s)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-trophy"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $inscriptions->where('status', 'pago')->count() }}</h3>
                    <p>Inscrições disciplina(s)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-star"></i>
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
                    <h3>{{ $registrations->where('payment', 'no')->count() }}</h3>
                    <p>Matrícula(s) pendente(s)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-down"></i>
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
                            <img class="profile-user-img img-fluid img-circle" src="https://dummyimage.com/28x28/b6b7ba/fff"
                                alt="Photo">
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
                            <b>Matrícula</b> <a class="float-right">{{ $student->registration }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Cidade/UF</b> <a class="float-right">{{ $student->city . '/' . $student->state }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Igreja</b> <a class="float-right">{{ $student->church }}</a>
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
                    <a href="{{ route('admin.student.edit', ['id' => $student->id]) }}"
                        class="btn btn-primary btn-block"><b>Editar registro</b></a>
                </div>
            </div>

        </div>
        <div class="col-md-6">

            <div class="row">
                @forelse ($registrations as $registration)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ $registration->course->title }}
                                </h3>
                            </div>

                            <div class="card-body p-0">
                                <div
                                    style="width: 100%; height: 200px; background-image: url('{{ asset('storage/' . $registration->course->image) }}'); background-position: center; background-repeat: no-repeat; background-size: cover;">
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="d-block">{!! Str::substr($registration->course->description, 0, 130) !!}...</small>

                                <ul class="nav flex-column">
                                    <li class="nav-item">Instituição <span
                                            class="float-right">{{ $registration->course->institution }}</span></li>
                                    <li class="nav-item">Disciplinas <span
                                            class="float-right">{{ $registration->course->disciplines->count() }}</span>
                                    </li>
                                    <li class="nav-item">Duração <span
                                            class="float-right">{{ $registration->course->duration }}</span>
                                    </li>
                                    <li class="nav-item">Pagamento Matrícula
                                        <span class="float-right">
                                            @if ($registration->payment == 'yes')
                                                Confirmado
                                            @else
                                                Pendente
                                            @endif
                                        </span>
                                    </li>
                                </ul>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.student.historic',['slug' => $registration->course->slug, 'id' => $student->id]) }}" title="Histórico de atividades EAD do aluno"
                                        class="btn btn-info btn-sm mt-3">Histórico Acadêmico</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12 text-center">Nenhuma curso matrículado no momento.</div>
                @endforelse
            </div>
        </div>
        <div class="col-md-3">

            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Atendimentos solicitados</h3>
                </div>
                <div class="card-body p-0">
                    @if ($customerservices->whereIn('status', ['open', 'pending'])->count() > 0)
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($customerservices->whereIn('status', ['open', 'pending']) as $atendiment)
                                    <tr>
                                        <td class="py-2">
                                            <strong>{{ $atendiment->subject }}</strong><br />
                                            <small>{{ \Carbon\Carbon::parse($atendiment->created_at)->format('d/m/Y H:m:s') }}</small><br/>
                                            <i>{{ Str::substr($atendiment->description, 0, 250) }}...</i>
                                            <br />
                                            <a href="{{ route('admin.customerservice.show', ['id' => $atendiment->id]) }}"
                                                class="btn btn-xs btn-info mt-3"
                                                title="Clique para iniciar o atendimento.">Iniciar atendimento</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center m-0">Nenhum registro encontrado</p>
                    @endif
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Histórico de Matrículas</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <tbody>
                            @foreach ($registrations as $value)
                                <tr>
                                    <td class="py-2">
                                        <p style="line-height: 1; margin-bottom: 0">
                                            {{ $value->course->title }}<br />
                                            <small>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:m:s') }}</small><br />
                                            @if ($value->payment == 'no')
                                                <small>Aguardando pagamento</small><br />
                                                <a href="{{ route('admin.registration.edit', ['id' => $value->id]) }}"
                                                    class="btn btn-xs btn-info mt-2">Editar</a>
                                            @else
                                                <small>Pagamento realizado</small>
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Histórico de Inscrições</h3>
                </div>
                <div class="card-body p-0">
                    @if ($inscriptions->where('status', 'pendente')->count() > 0)
                        <table class="table table-striped">
                            <tbody>
                                @foreach ($inscriptions->where('status', 'pendente') as $value)
                                    <tr>
                                        <td class="py-2">
                                            <p style="line-height: 1; margin-bottom: 0">
                                                {{ $value->discipline->title }}<br />
                                                <small>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:m:s') }}</small><br />
                                                @if ($value->status == 'pendente')
                                                    <small>Aguardando pagamento</small><br />
                                                    <a href="{{ route('admin.inscription.edit', ['id' => $value->id]) }}"
                                                        class="btn btn-xs btn-info mt-2"
                                                        title="Clique para alterar os dados da inscrição">Editar</a>
                                                @else
                                                    <small>Pagamento realizado</small>
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center m-0">Nenhum registro encontrado</p>
                    @endif
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
