@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1 class="m-0">Sala de Estudo Digital <strong> {{ $discipline->title }}</strong></h1>
        <a href="/classroom/{{ $course->slug }}" class="btn btn-md btn-danger" title="Sair da sala de aula.">
            <i class="fa fa-times mr-1"></i> Sair do Forum
        </a>
    </div>
@stop

@section('content')

@php
    $comments = $forumcomments->where('user_id', auth()->user()->id)->count();
    $opnions = $forumopnions->where('user_id', auth()->user()->id)->count();
@endphp

    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Participantes</span>
                    <span class="info-box-number">{{ $inscriptions->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fa fa-comment"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Comentários no Forum</span>
                    <span class="info-box-number">{{ $comments }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fa fa-comments"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Opniões</span>
                    <span class="info-box-number">{{ $opnions }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-bullhorn"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Média participativa</span>
                    <span class="info-box-number">
                        @php
                            $media = $comments + $opnions;
                            if ($media <= 15) {
                                echo 'Participe mais!';
                            } else if ($media > 15 || $media <= 30) {
                                echo 'Boa participação!';
                            } else {
                                echo 'Ótima participação!';
                            }
                        @endphp 
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Forum
                                Acadêmico</a></li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Comentar no Forum</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">

                    <div class="mb-2">
                        {!! $forum->description !!}
                        <br />
                        <strong>Comentários do forum:</strong>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">

                            @forelse ($forumcomments as $comment)
                                <div class="post">
                                    <div class="user-block">
                                        @if (isset($comment->user->image))
                                            <img class="img-circle img-bordered-sm"
                                                src="{{ asset('storage/' . $comment->user->image) }}"
                                                alt="{{ $comment->user->name }}">
                                        @else
                                            <img class="img-circle img-bordered-sm"
                                                src="https://dummyimage.com/28x28/b6b7ba/fff" alt="User Image">
                                        @endif

                                        <span class="username">
                                            <a href="#">{{ $comment->user->name }}</a>
                                        </span>
                                        <span class="description">Publicado -
                                            {{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:m:s') }}</span>
                                    </div>

                                    <p class="mb-0">{{ $comment->comment }}</p>

                                    <p>
                                        <span class="float-right">
                                            <i class="far fa-comments mr-1"></i> 
                                            {{ $forumopnions->where('forum_comment_id', $comment->id)->count() }} Opniões
                                        </span>
                                    </p>

                                    <form method="POST" action="{{ route('livingroom.forum.opnion') }}">
                                        @csrf
                                        <div class="input-group">
                                            <input type="hidden" name="course_slug" value="{{ $course->slug }}" />
                                            <input type="hidden" name="discipline_slug" value="{{ $discipline->slug }}" />
                                            <input type="hidden" name="forum_comment_id" value="{{ $comment->id }}" />
                                            <input type="text" name="opnion" id="opinion" required
                                                placeholder="Deixe seu comentário." class="form-control">
                                            <span class="input-group-append">
                                                <button type="submit" id="send" class="btn btn-primary">Enviar
                                                    mensagem</button>
                                            </span>
                                        </div>
                                    </form>

                                    <div class="card-footer card-comments">
                                        @foreach ($forumopnions as $opnion)
                                            @if ($opnion->forum_comment_id == $comment->id)
                                                <div class="card-comment">
                                                    @if (isset($opnion->user->image))
                                                        <img src="{{ asset('storage/' . $opnion->user->image) }}"
                                                            alt="{{ $opnion->user->name }}" class="img-circle img-sm">
                                                    @else
                                                        <img src="/images/not-photo.jpg" alt="{{ $opnion->user->name }}"
                                                            class="img-circle img-sm">
                                                    @endif

                                                    <div class="comment-text">
                                                        <span class="username">
                                                            {{ $opnion->user->name }}
                                                            <span
                                                                class="text-muted float-right">{{ \Carbon\Carbon::parse($opnion->created_at)->format('d/m/Y H:m:s') }}</span>
                                                        </span>
                                                        {{ $opnion->opnion }}
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
                            <form method="POST" action="{{ route('livingroom.forum.comment') }}"
                                class="form-horizontal">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="course_slug" value="{{ $course->slug }}" />
                                        <input type="hidden" name="discipline_slug" value="{{ $discipline->slug }}" />
                                        <input type="hidden" name="forum_id" value="{{ $forum->id }}" />
                                        <input type="hidden" name="discipline_id" value="{{ $discipline->id }}" />
                                        <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" id="inputExperience"
                                            placeholder="Deixe aqui seu comentário no forum"></textarea>
                                        @error('comment')
                                            <small class="text-red line-height">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa fa-paper-plane mr-2"></i> Enviar comentário</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-3">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Participantes</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="users-list clearfix">
                        @forelse ($inscriptions as $inscription)
                        <li>
                            @if (isset($inscription->user->image))
                                <img src="{{ asset('storage/' . $inscription->user->image)}}" alt="User Image" style="width: 55px; height:55px">
                            @else
                                <img src="/images/not-photo.jpg" alt="User Image" style="width: 55px; height:55px">
                            @endif
                            <a class="users-list-name" href="#">{{ $inscription->user->name }}</a>
                            <span class="users-list-date">{{ $forumcomments->where('user_id', $inscription->user->id)->count() }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer text-center"></div>
            </div>

            <div class="timeline">
                <div class="time-label">
                    <span class="bg-info">Mural Informativo</span>
                </div>
                @php
                    $date_now = \Carbon\Carbon::now()->format('Y-m-d');
                @endphp
                @forelse ($murals as $mural)
                    @if ($date_now < $mural->date)
                        <div>
                            <i class="fa fa-comment bg-info"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header font-weight-bold">{{ $mural->title }}</h3>
                                <div class="timeline-body">{!! $mural->description !!}</div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div>
                        <i class="fa fa-comment bg-info"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock"></i>
                                {{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
                            <h3 class="timeline-header">Opsss!</h3>
                            <div class="timeline-body">Nunhum informativo disponível.</div>
                        </div>
                    </div>
                @endforelse
                <div>
                    <i class="fas fa-clock bg-gray"></i>
                </div>
            </div>

        </div>

    </div>

@stop

@section('css')

@stop

@section('js')

@stop
