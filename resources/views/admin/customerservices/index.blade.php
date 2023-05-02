@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex">
        <div>
            <form method="GET" action="{{ route('admin.customerservices.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <select name="sector" class="form-control mr-1">
                        <option value="">Setor</option>
                        <option value="support" @if ($search_sector == 'support') selected @endif>Suporte</option>
                        <option value="academic" @if ($search_sector == 'academic') selected @endif>Acadêmico</option>
                        <option value="financial" @if ($search_sector == 'financial') selected @endif>Financeiro</option>
                        <option value="complaint" @if ($search_sector == 'complaint') selected @endif>Reclamação</option>
                    </select>
                    <select name="status" class="form-control mr-1">
                        <option value="">Atendimento</option>
                        <option value="open" @if ($search_status == 'open') selected @endif>Abertos</option>
                        <option value="pending" @if ($search_status == 'pending') selected @endif>Pendente</option>
                        <option value="close" @if ($search_status == 'resolved') selected @endif>Resolvido</option>
                    </select>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>

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
                    <h3>{{ $records->count() }}</h3>
                    <p>Total de atendimentos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $records->where('status', 'open')->count() }}</h3>
                    <p>Atendimentos Abertos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $records->where('status', 'close')->count() }}</h3>
                    <p>Atendimentos Resolvidos</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-up"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $records->where('status', 'pending')->count() }}</h3>
                    <p>Atendimentos Pendentes</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-down"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de atendimentos ao público</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Aluno(a)</th>
                        <th class="py-2">Atendimento</th>
                        <th class="py-2 text-center">Setor</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customerservices as $customer)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($customer->user->image))
                                        <img src="{{ asset('storage/' . $customer->user->image) }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="{{ asset('images/not-photo.jpg') }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @endif
                                    <p style="line-height: 1; margin-bottom: 0">
                                        {{ $customer->user->name }}
                                        <br /><small>{{ $customer->user->phone . '  ' . $customer->user->email }}</small>
                                    </p>
                                </div>
                            </td>
                            <td class="py-2">
                                @if ($customer->sector == 'support')
                                    Suporte
                                @elseif($customer->sector == 'academic')
                                    Acadêmico
                                @elseif($customer->sector == 'financial')
                                    Financeiro
                                @else
                                    Reclamação
                                @endif
                            </td>
                            <td class="py-2 text-center">
                                @if ($customer->status == 'open')
                                    <small class="badge badge-warning px-3 py-1" title="">
                                        Aberto
                                    </small>
                                @elseif($customer->status == 'pending')
                                    <small class="badge badge-danger px-2 py-1" title="">
                                        Pendente
                                    </small>
                                @else
                                    <small class="badge badge-success px-2 py-1" title="">
                                        Resolvido
                                    </small>
                                @endif
                            </td>
                            <td class="py-2">{{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($customer->updated_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    @can('customerservice-menagement')
                                        <a href="{{ route('admin.customerservice.show', ['id' => $customer->id]) }}"
                                            class="btn btn-default btn-sm" title="Visualizar informações do atendimento.">
                                            <i class="fa fa-comment"></i>

                                            @if ($customer->customer_service_comments->where('view_student', 1)->count() > 0)
                                                <span
                                                    style="position: absolute; top: -7px; left: 2px; width: 12px; height: 14px; border-radius: 3px; background-color: #FFC107; color: #000000; padding: 0; font-size: 9px;">
                                                    {{ $customer->customer_service_comments->where('view_student', 1)->count() }}
                                                </span>
                                            @else
                                                <span
                                                    style="position: absolute; top: -7px; left: 2px; width: 12px; height: 14px; border-radius: 3px; background-color: #28A745; color: #FFFFFF; padding: 0; font-size: 9px;">
                                                    {{ $customer->customer_service_comments->where('view_student', 1)->count() }}
                                                </span>
                                            @endif

                                        </a>
                                    @endcan
                                    @can('customerservice-edit')
                                        <a href="{{ route('admin.customerservice.edit', ['id' => $customer->id]) }}"
                                            class="btn btn-default btn-sm" title="Editar registro">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('customerservice-delete')
                                        <a href="{{ route('admin.customerservice.destroy', ['id' => $customer->id]) }}"
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
                            <td class="py-2 text-center" colspan="7">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($customerservices)
                <div class="mt-2 mx-2">
                    {{ $customerservices->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif

            @foreach ($customerservices as $customer)
                <div class="modal fade" id="modal-lg{{ $customer->id }}" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Atendimento ao público</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body p-0">
                                <div class="card card-widget m-0">
                                    <div class="card-header">
                                        <div class="user-block">
                                            @if (isset($customer->user->image))
                                                <img class="img-circle"
                                                    src="{{ asset('storage/' . $customer->user->image) }}"
                                                    alt="User Image">
                                            @else
                                                <img class="img-circle" src="{{ asset('images/not-photo.jpg') }}"
                                                    alt="User Image">
                                            @endif

                                            <span class="username"><a
                                                    href="#">{{ $customer->user->name }}</a></span>
                                            <span
                                                class="description">{{ \Carbon\Carbon::parse($customer->updated_at)->format('d/m/Y H:m:s') }}</span>
                                        </div>
                                        <div class="card-tools"></div>
                                    </div>
                                    <div class="card-body">
                                        <label>{{ $customer->subject }}</label>
                                        <br />
                                        {{ $customer->description }}
                                    </div>

                                    <div class="card-footer card-comments">

                                        @forelse ($customer->customer_service_comments as $value)
                                            <div class="card-comment">

                                                @if (isset($value->user->image))
                                                    <img class="img-circle img-sm"
                                                        src="{{ asset('storage/' . $value->user->image) }}"
                                                        alt="User Image">
                                                @else
                                                    <img class="img-circle img-sm"
                                                        src="{{ asset('images/not-photo.jpg') }}" alt="User Image">
                                                @endif

                                                <div class="comment-text">
                                                    <span class="username">
                                                        {{ $value->user->name }}
                                                        <span class="text-muted float-right">
                                                            {{ \Carbon\Carbon::parse($value->updated_at)->format('d/m/Y H:m:s') }}
                                                        </span>
                                                    </span>
                                                    {{ $value->comment }}
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-center">Nenhum comentário adicionado.</p>
                                        @endforelse

                                    </div>

                                    <div class="card-footer">
                                        <form action="{{ route('admin.customerservice.comment') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="customer_service_id"
                                                value="{{ $customer->id }}" />
                                            <div class="input-group">
                                                <input type="text" name="comment" placeholder="Digite seu comentário"
                                                    class="form-control">
                                                <span class="input-group-append">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="fa fa-paper-plane mr-2"></i> Enviar</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end modal -->
            @endforeach

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
