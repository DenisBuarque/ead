@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h4 style="line-height: 1; margin-bottom: 0">{{ $forum->title }}</h4>
            <span>{{ $forum->course->title }}: {{ $forum->discipline->title }}</span>
        </div>
        <div>
            <a href="{{ route('admin.forums.index') }}" class="btn btn-md btn-info text-center"
                title="Listar registros de forum acadêmico">
                <i class="fa fa-list mr-1"></i> Listar Forums
            </a>
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
        <div class="col-md-8">

            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">
                            <i class="fa fa-bullhorn" aria-hidden="true"></i> Forum de participações</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">
                            <i class="fa fa-comment" aria-hidden="true"></i> Comentar no Forum</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="mb-2">
                        {!! $forum->description !!}
                        <strong>Comentários do forum:</strong>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">

                            @forelse ($forumcomments as $comment)
                                <div class="post">
                                    <div class="user-block">
                                        @if (isset($comment->user->image))
                                            <img class="img-circle img-bordered-sm"
                                                src="{{ asset('storage/' . $comment->user->image) }}" alt="Photo">
                                        @else
                                            <img class="img-circle img-bordered-sm" src="/images/not-photo.jpg"
                                                alt="User Image">
                                        @endif

                                        <span class="username">
                                            <a href="#">{{ $comment->user->name }}</a>
                                        </span>
                                        <span class="description">
                                            {{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:m:s') }}
                                        </span>
                                    </div>

                                    <p class="mb-0">{{ $comment->comment }}</p>

                                    <p>
                                        <span class="float-right">
                                            <i class="far fa-comments mr-1"></i> 
                                            {{ $opnionscomment->where('comment_id', $comment->id)->count() }}
                                            opniões(s)
                                        </span>
                                    </p>

                                    <form method="POST" action="{{ route('admin.forum.opinions') }}">
                                        @csrf
                                        <div class="input-group">
                                            <input type="hidden" name="forum_id" value="{{ $forum->id }}" />
                                            <input type="hidden" name="forum_comment_id" value="{{ $comment->id }}" />
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
                                            <input type="text" name="opinion" id="opinion" required
                                                placeholder="Deixe seu comentário." class="form-control">
                                            <span class="input-group-append">
                                                <button type="submit" id="send"
                                                    class="btn btn-primary">Enviar</button>
                                            </span>
                                        </div>
                                    </form>

                                    <div class="card-footer card-comments">
                                        @foreach ($opnionscomment as $opnion)
                                            @if ($opnion->forum_comment_id == $comment->id)
                                                <div class="card-comment">

                                                    @if (isset($opnion->user->image))
                                                        <img class="img-circle img-sm"
                                                            src="{{ asset('storage/' . $opnion->user->image) }}"
                                                            alt="{{ $opnion->user->name }}">
                                                    @else
                                                        <img class="img-circle img-sm"
                                                            src="/images/not-photo.jpg" alt="User Image">
                                                    @endif

                                                    <div class="comment-text">
                                                        <span class="username">
                                                            {{ $opnion->user->name }}
                                                            <span
                                                                class="text-muted float-right">{{ \Carbon\Carbon::parse($opnion->created_at)->format('d/m/Y H:m:s') }}</span>
                                                        </span>
                                                        {{ $opnion->opinion }}
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                </div>
                            @empty
                                <div class="text-center">Nenhum comentário adicionado.</div>
                            @endforelse
                        </div>

                        <div class="tab-pane" id="settings">
                            <form method="POST" action="{{ route('admin.forum.comments') }}" class="form-horizontal">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="forum_id" value="{{ $forum->id }}" />
                                        <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" id="inputExperience"
                                            placeholder="Deixe aqui seu comentário no forum."></textarea>
                                        @error('comment')
                                            <small class="text-red line-height">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-danger">Enviar Comentário</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Participação de alunos</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="users-list clearfix">
                        @forelse ($inscriptions as $inscription)
                            <li>
                                @if (isset($inscription->user->image))
                                    <img src="{{ asset('storage/' . $inscription->user->image) }}" alt="Image"
                                        style="width: 70px; height: 70px;">
                                @else
                                    <img src="/images/not-photo.jpg" alt="Image" style="width: 70px; height: 70px;">
                                @endif
                                <a class="users-list-name" href="#">{{ $inscription->user->name }}</a>
                                <span class="users-list-date">{{ $inscription->user->forum_comments->count() }} coment.</span>
                            </li>
                        @empty
                            <li>
                                <span>Lista vazia</span>
                            </li>
                        @endforelse
                    </ul>
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
