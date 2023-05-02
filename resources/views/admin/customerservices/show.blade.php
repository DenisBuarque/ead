@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h3>Atendimento ao estudante</h3>
        <a href="{{ route('admin.customerservices.index') }}" class="btn btn-md btn-info" title="Listar atendimentos de alunos">
            <i class="fa fa-list mr-1"></i> Listar atendimentos
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
                    <h3>{{ $records->count() }}</h3>
                    <p>Total de atendimentos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
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
                    <i class="fa fa-thumbs_down"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card card-widget">
                <div class="card-header">
                    <div class="user-block">
                        @if (isset($atendiment->user->image))
                            <img class="img-circle" src="{{ asset('storage/' . $atendiment->user->image) }}"
                                alt="User Image">
                        @else
                            <img class="img-circle" src="/images/not-photo.jpg" alt="User Image">
                        @endif

                        <span class="username">{{ $atendiment->user->name }}</span>
                        <span class="description">{{ $atendiment->status }} -
                            {{ \Carbon\Carbon::parse($atendiment->created_at)->format('d/m/Y H:m:s') }}</span>
                    </div>
                    <div class="card-tools"></div>
                </div>
                <div class="card-body">
                    <strong>{{ $atendiment->subject }}</strong>
                    <p>{{ $atendiment->description }}</p>
                    <a href="{{ route('admin.customerservice.edit', ['id' => $atendiment->id]) }}" class="btn btn-default">Editar Atendimento</a>
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.customerservice.comment') }}" method="post">
                        @csrf
                        <input type="hidden" name="customer_service_id" value="{{ $atendiment->id }}" />
                        <div class="input-group">
                            <input type="text" name="comment" placeholder="Digite seu comentário" class="form-control">
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane mr-2"></i>
                                    Enviar</button>
                            </span>
                        </div>
                    </form>
                </div>

                <div class="card-footer card-comments">
                    <div class="card-comment">
                        @foreach ($customercomments as $comment)
                            @if (isset($comment->user->image))
                                <img class="img-circle img-sm" src="{{ asset('storage/' . $comment->user->image) }}"
                                    alt="Photo" />
                            @else
                                <img class="img-circle img-sm" src="/images/not-photo.jpg" alt="Photo" />
                            @endif
                            <div class="comment-text">
                                <span class="username">
                                    {{ $comment->user->name }}
                                    <span
                                        class="text-muted float-right">{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:m:s') }}</span>
                                </span>
                                <p>{{ $comment->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
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
