@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Atendimento ao Aluno</h1>
        <a href="{{ route('classroom.new.customer.service') }}" class="btn btn-md btn-info"
            title="Clique para criar ticket de atendimento pessoal">
            <i class="fa fa-comment mr-1"></i> Solicitar novo atendimento
        </a>
    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fa fa-comments"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de atendimentos</span>
                    <span class="info-box-number">{{ $customerservices->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fa fa-comment"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Atendimento abertos</span>
                    <span class="info-box-number">{{ $customerservices->where('status', 'open')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fa fa-thumbs-down"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Atendimentos Pendentes</span>
                    <span class="info-box-number">{{ $customerservices->where('status', 'pending')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fa fa-thumbs-up"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Atendimentos resolvidos</span>
                    <span class="info-box-number">{{ $customerservices->where('status', 'resolved')->count() }}</span>
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
                            <img src="{{ asset('storage/' . $student->image) }}"
                                class="profile-user-img img-fluid img-circle" alt="User Photo"
                                style="width: 100px; height: 100px;">
                        @else
                            <img src="/images/not-photo.jpg" class="profile-user-img img-fluid img-circle" alt="User Photo"
                                style="width: 100px; height: 100px;">
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Meus atendimentos realizados</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th class="text-center">Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customerservices as $service)
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <span>
                                                @if ($service->sector == 'support')
                                                    Suporte
                                                @elseif($service->sector == 'academic')
                                                    Acadêmico
                                                @elseif($service->sector == 'financial')
                                                    Financeiro
                                                @else
                                                    Reclamação
                                                @endif
                                            </span>
                                            <span>
                                                {{ \Carbon\Carbon::parse($service->created_at)->format('d/m/Y H:m:s') }}
                                            </span>
                                        </div>
                                        <p style="line-height: 1; margin-bottom: 20px;">
                                            <strong>{{ $service->subject }}</strong>
                                            <br /><small>{{ $service->description }}</small>
                                        </p>
                                        <div class="btn-group">
                                            @if ($service->customer_service_comments->where('view_user', 1)->count() > 0)
                                                <a href="" class="btn btn-default btn-sm" data-toggle="modal"
                                                    data-target="#modal-lg{{ $service->id }}"
                                                    title="Clique para acessar os comentários do atendimento">
                                                    <i class="fa fa-comments"></i> Abrir atendimento
                                                    <span
                                                        style="position: absolute; top: -7px; left: 2px; width: 12px; height: 14px; border-radius: 3px; background-color: #ffc107; color: #FFFFFF; padding: 0; font-size: 9px;">
                                                        {{ $service->customer_service_comments->count() }}
                                                    </span>
                                                </a>
                                            @else
                                                <a href="" class="btn btn-default btn-sm" data-toggle="modal"
                                                    data-target="#modal-lg{{ $service->id }}"
                                                    title="Clique para acessar os comentários do atendimento">
                                                    <i class="fa fa-comments"></i> Abrir atendimento
                                                    <span
                                                        style="position: absolute; top: -7px; left: 2px; width: 12px; height: 14px; border-radius: 3px; background-color: #17a2b8; color: #FFFFFF; padding: 0; font-size: 9px;">
                                                        {{ $service->customer_service_comments->count() }}
                                                    </span>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($service->status == 'open')
                                            <small class="badge badge-warning px-3">Aberto</small>
                                        @elseif($service->status == 'pending')
                                            <small class="badge badge-danger">Pendente</small>
                                        @else
                                            <small class="badge badge-success">Resolvido</small>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="5">Nenhum atendimento solicitado no momento</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>


                    @foreach ($customerservices as $service)
                        <div class="modal fade" id="modal-lg{{ $service->id }}" style="display: none;"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Comentários de Atendimento</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body p-0">

                                        <div class="card card-widget m-0">

                                            <div class="card-header">
                                                <div class="user-block">
                                                    @if (isset($service->user->image))
                                                        <img class="img-circle"
                                                            src="{{ asset('storage/' . $service->user->image) }}"
                                                            alt="Image" />
                                                    @else
                                                        <img class="img-circle" src="/images/not-photo.jpg"
                                                            alt="Image" />
                                                    @endif
                                                    <span class="username">{{ $service->user->name }}</span>
                                                    <span class="description">{{ $service->subject }}</span>
                                                </div>
                                                <div class="card-tools"></div>
                                            </div>

                                            <div class="card-body">
                                                <p>{{ $service->description }}</p>
                                            </div>

                                            <div class="card-footer">
                                                <form method="POST"
                                                    action="{{ route('classroom.atendiment.comment.store') }}">
                                                    @csrf
                                                    <input type="hidden" name="customer_service_id"
                                                        value="{{ $service->id }}" />
                                                    <div class="input-group">
                                                        <input type="text" name="comment" required
                                                            placeholder="Digite seu comentário" class="form-control">
                                                        <span class="input-group-append">
                                                            <button type="submit" class="btn btn-primary">Enviar</button>
                                                        </span>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="card-footer card-comments">

                                                @forelse ($service->customer_service_comments as $value)
                                                    <div class="card-comment">
                                                        @if (isset($value->user->image))
                                                            <img class="img-circle img-sm"
                                                                src="{{ asset('storage/' . $value->user->image) }}"
                                                                alt="Image" />
                                                        @else
                                                            <img class="img-circle img-sm" src="/images/not-photo.jpg"
                                                                alt="Image" />
                                                        @endif
                                                        <div class="comment-text">
                                                            <span class="username">
                                                                {{ $value->user->name }}
                                                                <span class="text-muted float-right">
                                                                    {{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:m:s') }}
                                                                </span>
                                                            </span>
                                                            <p>{{ $value->comment }}</p>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-center">Nenhum comentário adicionado.</p>
                                                @endforelse

                                            </div>
                                        </div>
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
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
