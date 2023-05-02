@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    @if (auth()->user()->nivel == 'student')

        <p>Bem-vindo a sua plataforma de ensino.</p>

        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fa fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Cursos matrículados</span>
                        <span
                            class="info-box-number">{{ $registrations->where('payment', 'yes')->where('user_id', auth()->user()->id)->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fa fa-star"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Inscrições realizadas</span>
                        <span
                            class="info-box-number">{{ $inscriptions->where('user_id', auth()->user()->id)->where('status', 'pago')->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Mural informativo</span>
                        <span class="info-box-number">0</span>
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
                                {{ $customerservices->where('user_id', auth()->user()->id)->whereIn('status', ['open', 'pending'])->count() }}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if (isset($student->image))
                                <img src="{{ asset('storage/' . $student->image) }}"
                                    class="profile-user-img img-fluid img-circle" alt="User Photo"
                                    style="width: 100px; height: 100px;">
                            @else
                                <img src="/images/not-photo.jpg" class="profile-user-img img-fluid img-circle"
                                    alt="User Photo" style="width: 100px; height: 100px;">
                            @endif
                        </div>
                        <h3 class="profile-username text-center">{{ $student->name }}</h3>
                        <p class="text-muted text-center">Estudante</p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Instituição</b> <a class="float-right">{{ $student->institution }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Ano</b> <a class="float-right">{{ $student->year }}º ano</a>
                            </li>
                            <li class="list-group-item">
                                <b>Data de entrada</b> <a
                                    class="float-right">{{ \Carbon\Carbon::parse($student->date_entry)->format('d/m/Y') }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Data de saída</b> <a
                                    class="float-right">{{ \Carbon\Carbon::parse($student->date_exit)->format('d/m/Y') }}</a>
                            </li>
                        </ul>
                        <a href="{{ route('dashboard.student.edit', ['id' => $student->id]) }}"
                            class="btn btn-primary btn-block"><b>Meu perfil</b></a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Atendimento Direct Chat</h3>
                        <div class="card-tools">
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <ul class="users-list clearfix">
                            @foreach ($directchats->where('user_id', auth()->user()->id)->where('active', 1) as $chat)
                                <li>
                                    @if (isset($chat->user->image))
                                        <a class="users-list-name"
                                            href="/livingroom/{{ $chat->course->slug }}/{{ $chat->discipline->slug }}"><img
                                                src="{{ asset('storage/' . $chat->user->image) }}"
                                                title="{{ $chat->course->title }}" alt="Image"
                                                style="width: 60px; height: 60px" /></a>
                                    @else
                                        <a class="users-list-name"
                                            href="/livingroom/{{ $chat->course->slug }}/{{ $chat->discipline->slug }}"><img
                                                src="/images/not-photo.jpg" alt="Image"
                                                title="{{ $chat->course->title }}" style="width: 60px; height: 60px" /></a>
                                    @endif
                                    <a class="users-list-name"
                                        href="/livingroom/{{ $chat->course->slug }}/{{ $chat->discipline->slug }}">{{ $chat->discipline->title }}
                                        msg</a>
                                    <span title="Novas mensagens"
                                        class="badge badge-default">{{ $chat->direct_chat_messages->where('check_admin', true)->count() }}
                                        msg</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Matrículas</h3>
                        <div class="card-tools">
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody>
                                    @forelse ($registrations->where('user_id', auth()->user()->id) as $registration)
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    @if ($registration->payment == 'yes')
                                                        <small class="text-primary"><b>Acesso liberado</b></small><br />
                                                    @else
                                                        <small class="text-danger"><b>Matrícula em
                                                                Processo</b></small><br />
                                                    @endif
                                                    <small>{{ \Carbon\Carbon::parse($registration->created_at)->format('d/m/Y H:m:s') }}</small>
                                                </div>
                                                <p style="line-height: 1; margin-bottom: 0">

                                                    {{ $registration->course->title }}<br />
                                                    <small>{{ $registration->user->name }}</small>

                                                </p>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center">Nunhuma pré matrúcula solicitada</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
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

                <div class="row">
                    @forelse ($courses as $course)
                        <div class="col-md-4">

                            <div class="card">
                                <div class="card-header p-0">
                                    @if (isset($course->image))
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $course->image) }}" alt="Photo"
                                                class="img-fluid">
                                            <div class="ribbon-wrapper ribbon-lg">



                                                @if ($registrations->where('user_id', auth()->user()->id)->where('course_id', $course->id)->where('payment', 'yes')->count() == 1)
                                                    <div class="ribbon bg-primary text-lg">
                                                        Matric.
                                                    </div>
                                                @else
                                                    @if ($registrations->where('user_id', auth()->user()->id)->where('course_id', $course->id)->where('payment', 'no')->count() == 1)
                                                        <div class="ribbon bg-warning text-lg">
                                                            Process.
                                                        </div>
                                                    @else
                                                        <div class="ribbon bg-success text-lg">
                                                            Pré Mat.
                                                        </div>
                                                    @endif
                                                @endif

                                            </div>
                                        </div>
                                    @else
                                        <img src="/images/not-photo.jpg" alt="photo"
                                            style="width: 100%; height: 200px" />
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <small>{{ $course->duration }}</small>
                                        <small>{{ $course->institution }}</small>
                                    </div>
                                    <h4>{{ $course->title }}</h4>
                                    <small>{!! $course->description !!}</small>
                                </div>
                                <div class="card-footer">
                                    @if ($registrations->where('user_id', auth()->user()->id)->where('course_id', $course->id)->where('payment', 'yes')->count() == 1)
                                        <a href="{{ route('classroom', ['slug' => $course->slug]) }}"
                                            class="btn btn-sm bg-primary"
                                            title="Clique para acessar a sala de aula do curso">Acessar meu curso</a>
                                    @else
                                        @if ($registrations->where('user_id', auth()->user()->id)->where('course_id', $course->id)->where('payment', 'no')->count() == 1)
                                            <button type="button" class="btn btn-sm btn-default"
                                                title="Sua matrícula esta em porcesso de análise">Matrícula em
                                                processo</button>
                                        @else
                                            <button type="button" class="btn btn-sm bg-success" data-toggle="modal"
                                                data-target="#modal-default{{ $course->id }}"
                                                title="Clique para solicitar sua pré matrícula no curso">Fazer pré
                                                matrícula</button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12 text-center">
                            <p>Nenhum curso disponível no momento.</p>
                        </div>
                    @endforelse
                </div>

                @forelse ($courses as $course)
                    <div class="modal fade" id="modal-default{{ $course->id }}" style="display: none;"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Pré Matrícula</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Você está realizando sua pré matrícula no curso
                                        <strong>{{ $course->title }}</strong>, após sua solicitação aguarde nosso contato.
                                    </p>
                                    <i>{!! $course->description !!}</i>
                                </div>
                                <form method="POST" action="{{ route('dashboard.store.registration') }}">
                                    @csrf
                                    <div class="modal-footer justify-content-between">
                                        <input type="hidden" name="course_id" value="{{ $course->id }}" />
                                        <button type="submit" class="btn btn-default"
                                            data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Realizar pré matrícula</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    @endif

    @if (auth()->user()->nivel == 'admin')

        <p>Bem-vindo ao painel administrativo da plataforma.</p>

        <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-user"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Administradores</span>
                        <span class="info-box-number">{{ $users->where('nivel', 'admin')->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-flag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Polos de ensino</span>
                        <span class="info-box-number">{{ $polos->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-graduation-cap"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Cursos</span>
                        <span class="info-box-number">{{ $courses->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Disciplinas</span>
                        <span class="info-box-number">{{ $disciplines->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $preregistrations->count() }}</h3>
                        <p>Pré Matrículas</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-address-card"></i>
                    </div>
                    <a href="{{ route('admin.preregistrations.index') }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $registrations->where('payment', 'no')->count() }}</h3>
                        <p>Matrículas</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-trophy"></i>
                    </div>
                    <a href="{{ route('admin.registrations.index') }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $inscriptions->where('status', 'pendente')->count() }}</h3>
                        <p>Inscrições</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-star"></i>
                    </div>
                    <a href="{{ route('admin.inscriptions.index') }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $customerservices->whereIn('status', ['open', 'pending'])->count() }}</h3>
                        <p>Atendimentos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-comment"></i>
                    </div>
                    <a href="{{ route('admin.customerservices.index') }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Pré Matrículas</h3>
                        <div class="card-tools">
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody>
                                    @forelse ($preregistrations as $value)
                                        <tr>
                                            <td>
                                                <p style="line-height: 1; margin-bottom: 0">
                                                    {{ $value->name }}<br />
                                                    <small>
                                                        {{ $value->phone . ' ' . $value->email }}<br />
                                                        {{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:m:s') }}
                                                    </small>
                                                </p>
                                                <a href="{{ route('admin.preregistration.edit', ['id' => $value->id]) }}"
                                                    class="btn btn-xs btn-default mt-2"
                                                    title="Clique para alterar a solicitação de matrícula">Editar</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center">Nunhuma pré matrúcula encontrada</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Trabalhos Acadêmicos</h3>
                        <div class="card-tools">
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody>
                                    @forelse ($jobs as $job)
                                        <tr>
                                            <td>
                                                <p style="line-height: 1; margin-bottom: 0">
                                                    {{ $job->user->name }}<br />
                                                    <small title="{{ $job->course->title }}">
                                                        {{ $job->discipline->title }}<br />
                                                        {{ \Carbon\Carbon::parse($job->created_at)->format('d/m/Y H:m:s') }}
                                                    </small>
                                                </p>
                                                <a href="/admin/student/historic/ead/{{ $job->course->slug }}/{{ $job->discipline->slug }}/{{ $job->user->id }}"
                                                    class="btn btn-xs btn-default mt-2"
                                                    title="Clique para alterar a solicitação de matrícula">Pontuar</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center">Nunhuma nota disponível</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Questões abertas</h3>
                        <div class="card-tools">
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <tbody>
                                    @forelse ($openresponses as $openres)
                                        <tr>
                                            <td>
                                                <p style="line-height: 1; margin-bottom: 0">
                                                    {{ $openres->user->name }}<br />
                                                    <small title="{{ $openres->course->title }}">
                                                        {{ $openres->discipline->title }}<br />
                                                        {{ \Carbon\Carbon::parse($openres->created_at)->format('d/m/Y H:m:s') }}
                                                    </small>
                                                </p>
                                                <a href="/admin/student/historic/ead/{{ $openres->course->slug }}/{{ $openres->discipline->slug }}/{{ $openres->user->id }}"
                                                    class="btn btn-xs btn-default mt-2"
                                                    title="Clique para alterar a solicitação de matrícula">Pontuar
                                                    disciplina</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center">Nunhuma nota disponível</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Mensagens do Direct Chat</h3>
                        <div class="card-tools"></div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="users-list clearfix">
                            @foreach ($directchats->where('active', 1) as $value)
                                <li>
                                    @if (isset($value->user->image))
                                        <a class="users-list-name"
                                            href="/admin/student/historic/ead/{{ $value->course->slug }}/{{ $value->discipline->slug }}/{{ $value->user->id }}">
                                            <img src="{{ asset('storage/' . $value->user->image) }}" alt="Image"
                                                title="{{ $value->user->name }}" style="width: 100px; height: 100px;" />
                                        </a>
                                    @else<a class="users-list-name"
                                            href="/admin/student/historic/ead/{{ $value->course->slug }}/{{ $value->discipline->slug }}/{{ $value->user->id }}">
                                            <img src="/images/not-photo.jpg" alt="Image"
                                                style="width: 100px; height: 100px;" />
                                        </a>
                                    @endif
                                    <a class="users-list-name" title="{{ $value->course->title }}"
                                        href="/admin/student/historic/ead/{{ $value->course->slug }}/{{ $value->discipline->slug }}/{{ $value->user->id }}">{{ $value->discipline->title }}</a>
                                    <span
                                        class="badge badge-default">{{ $value->direct_chat_messages->where('check_student', true)->count() }}
                                        msg</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="card-footer text-center">
                        <a href="{{ route('admin.students.index') }}">Listar todos os alunos</a>
                    </div>

                </div>

                <div class="row">
                    @foreach ($customerservices->whereIn('status', ['open', 'pending']) as $atendiment)
                        <div class="col-md-6">
                            <div class="card bg-light d-flex flex-fill">
                                <div class="card-header text-muted border-bottom-0">
                                    Atendimento
                                    @if ($atendiment->status == 'open')
                                        <span class="badge badge-warning">Aberto</span>
                                    @else
                                        <span class="badge badge-danger">Pendente</span>
                                    @endif
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-7">
                                            <h2 class="lead"><b>{{ $atendiment->user->name }}</b></h2>
                                            <p class="text-muted text-sm">{{ $atendiment->subject }}</p>
                                            <ul class="ml-0 mb-0 fa-ul text-muted">
                                                <li class="small">
                                                    {{ \Carbon\Carbon::parse($atendiment->created_at)->format('d/m/Y H:m:s') }}
                                                </li>
                                                <li class="small">{{ $atendiment->user->institution }}</li>
                                            </ul>
                                        </div>
                                        <div class="col-5 text-center">
                                            @if (isset($atendiment->user->image))
                                                <img src="{{ asset('storage/' . $atendiment->user->image) }}"
                                                    alt="user-avatar" class="img-circle img-fluid"
                                                    style="width: 100px; height: 100px" />
                                            @else
                                                <img src="images/not-photo.jpg" alt="user-avatar"
                                                    class="img-circle img-fluid" style="width: 100px; height: 100px" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-right">
                                        <a href="{{ route('admin.customerservice.show', ['id' => $atendiment->id]) }}"
                                            class="btn btn-sm bg-teal" title="Iniciar atendimento de suporte">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                        <a href="{{ route('admin.student.edit', ['id' => $atendiment->user_id]) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-user"></i> Perfil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="col-md-3">

                <div class="info-box mb-3 bg-primary">
                    <span class="info-box-icon"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total de alunos</span>
                        <span class="info-box-number">{{ $users->where('nivel', 'student')->count() }}</span>
                    </div>

                </div>

                <div class="info-box mb-3 bg-info">
                    <span class="info-box-icon"><i class="fa fa-address-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Matrículas realizadas</span>
                        <span class="info-box-number">{{ $registrations->where('payment', 'yes')->count() }}</span>
                    </div>

                </div>

                <div class="info-box mb-3 bg-success">
                    <span class="info-box-icon"><i class="fa fa-address-card"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Inscrições realizadas</span>
                        <span class="info-box-number">{{ $inscriptions->where('status', 'pago')->count() }}</span>
                    </div>

                </div>

                <div class="info-box mb-3 bg-warning">
                    <span class="info-box-icon"><i class="far fa-comment"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Atendimentos resolvidos</span>
                        <span
                            class="info-box-number">{{ $customerservices->whereIn('status', 'resolved')->count() }}</span>
                    </div>

                </div>

            </div>
        </div>

    @endif

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
