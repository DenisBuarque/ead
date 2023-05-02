@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3 class="mb-0">Histórico de atividades {{ $discipline->title }}</h3>
        </div>
        <a href="/admin/student/historic/{{ $course->slug }}/{{ $student->id }}" class="btn btn-md btn-info">
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
                    <h3>{{ $course->registrations->count() }}</h3>
                    <p>Curso(s) Matriculado(s)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-trophy"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $discipline->inscriptions->count() }}</h3>
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
                    <h3>{{ $forumopnions->count() }}</h3>
                    <p>Participações forum</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    @if (!empty($directchat->direct_chat_messages))
                        <h3>{{ $directchat->direct_chat_messages->count() }}</h3>
                    @else
                        <h3>0</h3>
                    @endif
                    <p>Comentários chat</p>
                </div>
                <div class="icon">
                    <i class="fa fa-comments"></i>
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
                            <img class="profile-user-img img-fluid img-circle" style="width: 100px; height: 100px"
                                src="{{ asset('storage/' . $student->image) }}" alt="Photo">
                        @else
                            <img class="profile-user-img img-fluid img-circle" style="width: 100px; height: 100px"
                                src="/images/not-photo.jpg" alt="Photo">
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
                        class="btn btn-primary btn-block"><b>Perfil estudante</b></a>
                </div>
            </div>

        </div>

        <div class="col-md-6">

            <div class="card card-primary" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
                <div class="card-header">
                    <h3 class="card-title">Respostas Multiplas Escolhas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body p-0" style="display: block;">
                    @if ($multiple_response->count() > 0)
                        <form method="POST" action="{{ route('admin.dashboard.destroy.multipleresponse') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="course" value="{{ $course->slug }}" />
                            <input type="hidden" name="discipline" value="{{ $discipline->slug }}" />
                            <input type="hidden" name="course_id" value="{{ $course->id }}" />
                            <input type="hidden" name="discipline_id" value="{{ $discipline->id }}" />
                            <input type="hidden" name="user_id" value="{{ $student->id }}" />
                            <button type="submit" class="btn btn-default btn-sm m-3"
                                onclick="return confirmaExcluir()">Restaurar Questões</button>
                        </form>
                    @endif

                    <table class="table table-striped">
                        <tbody>
                            @forelse ($multiple_response as $result)
                                <tr>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($result->created_at)->format('d/m/Y H:m:s') }}</small><br />
                                        <p style="line-height: 1; margin-bottom: 0">
                                            <strong>{{ $result->multiple_question->title }}</strong>
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <span>
                                                {{ 'Gabarito: ' . $result->gabarito . 'º / Resposta do aluno: ' . $result->option . 'º' }}<br />
                                            </span>
                                            @if ($result->gabarito == $result->option)
                                                <span
                                                    class="badge badge-success px-3 d-flex align-items-center">Acertou</span>
                                            @else
                                                <span
                                                    class="badge badge-danger px-4 d-flex align-items-center">Errou</span>
                                            @endif
                                        </div>

                                        <p style="line-height: 1; margin-top: 10px">
                                            1º {{ $result->multiple_question->response_one }}<br />
                                            2º {{ $result->multiple_question->response_two }}<br />
                                            3º {{ $result->multiple_question->response_tree }}<br />
                                            4º {{ $result->multiple_question->response_four }}
                                        </p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center">Aguardando respostas da avaliação.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Resposta Questões Abertas
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">

                    @if ($open_response->count() > 0)
                        <form method="POST" action="{{ route('admin.dashboard.destroy.openresponse') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="course" value="{{ $course->slug }}" />
                            <input type="hidden" name="discipline" value="{{ $discipline->slug }}" />
                            <input type="hidden" name="course_id" value="{{ $course->id }}" />
                            <input type="hidden" name="discipline_id" value="{{ $discipline->id }}" />
                            <input type="hidden" name="user_id" value="{{ $student->id }}" />
                            <button type="submit" class="btn btn-default btn-sm m-3"
                                onclick="return confirmaExcluir()">Restaurar Questões</button>
                        </form>
                    @endif

                    <table class="table table-striped">
                        <tbody>
                            @forelse ($open_response as $result)
                                <tr>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($result->created_at)->format('d/m/Y H:m:s') }}</small><br />
                                        <p style="line-height: 1; margin-bottom: 0">
                                            <strong>{{ $result->open_question->title }}</strong>
                                        </p>
                                        <p style="line-height: 1; margin-top: 10px">{{ $result->resposta }}</p>
                                        <form method="POST"
                                            action="{{ route('admin.evaluation.opennote.update', ['id' => $result->id]) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group" style="width: 170px">
                                                <input type="hidden" name="course" value="{{ $course->slug }}" />
                                                <input type="hidden" name="discipline"
                                                    value="{{ $discipline->slug }}" />
                                                <input type="hidden" name="user_id" value="{{ $result->user_id }}" />
                                                <input type="text" name="note" id="note"
                                                    value="{{ $result->note }}" placeholder="0.0" maxlength="5"
                                                    class="form-control" />
                                                <span class="input-group-append">
                                                    <button type="submit" class="btn btn-info"
                                                        id="saveNote">Pontuar</button>
                                                </span>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center">Aguardando respostas da avaliação.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if (isset($forum))

                <div class="card card-primary">

                    <div class="card-header">
                        <h3 class="card-title">Forum Acadêmico
                        </h3>
                        <div class="card-tools"></div>
                    </div>

                    <div class="card-body">
                        <div class="mb-2">
                            {!! $forum->description !!}
                            <br />
                            <strong>Comentários do forum:</strong>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="activity">
                                @forelse ($forum->forum_comments as $comment)
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
                                                {{ $forumopnions->where('forum_comment_id', $comment->id)->count() }}
                                                Opniões
                                            </span>
                                        </p>
                                        <div class="card-footer card-comments">
                                            @foreach ($forumopnions as $opnion)
                                                @if ($opnion->forum_comment_id == $comment->id)
                                                    <div class="card-comment">
                                                        @if (isset($opnion->user->image))
                                                            <img src="{{ asset('storage/' . $opnion->user->image) }}"
                                                                alt="{{ $opnion->user->name }}"
                                                                class="img-circle img-sm">
                                                        @else
                                                            <img src="/images/not-photo.jpg"
                                                                alt="{{ $opnion->user->name }}"
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
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="col-md-3">

            @if (!$directchat)
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header">
                        <h3 class="card-title">Direct Chat</h3>
                        <div class="card-tools"></div>
                    </div>

                    <div class="card-body p-4">
                        <p>Digite o assunto do chat para iniciar uma conversa:</p>
                    </div>

                    <div class="card-footer">
                        <form action="{{ route('student.start.directchat') }}" method="post">
                            @csrf
                            <div class="input-group">
                                <input type="hidden" name="course_slug" value="{{ $course->slug }}" />
                                <input type="hidden" name="discipline_slug" value="{{ $discipline->slug }}" />
                                <input type="hidden" name="user_id" value="{{ $student->id }}" />
                                <input type="hidden" name="course_id" value="{{ $course->id }}" />
                                <input type="hidden" name="discipline_id" value="{{ $discipline->id }}" />
                                <input type="text" name="subject" placeholder="Assunto do chat." class="form-control"
                                    required />
                                <span class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Inciar chat</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header">
                        <h3 class="card-title">Direct Chat</h3>
                        <div class="card-tools">
                            <form action="{{ route('admin.student.close.chat',['id' => $directchat->id]) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <input type="hidden" name="course" value="{{ $course->slug }}" />
                                    <input type="hidden" name="discipline" value="{{ $discipline->slug }}" />
                                    <input type="hidden" name="user" value="{{ $student->id }}" />
                                    <input type="hidden" name="active" value="0" />
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-xs btn-danger">Encerrar Chat</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <br/>
                        <small>{{ $directchat->subject }}</small>
                    </div>
                    <div class="card-body">

                        <div class="direct-chat-messages" id="direct-chat-messages">

                            @forelse ($directchat->direct_chat_messages as $message)
                                @if ($message->user_id == $student->id)
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">{{ $message->user->name }}</span>
                                            <span
                                                class="direct-chat-timestamp float-right">{{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:m:s') }}</span>
                                        </div>
                                        @if (isset($message->user->image))
                                            <img class="direct-chat-img"
                                                src="{{ asset('storage/' . $message->user->image) }}"
                                                alt="Message User Image">
                                        @else
                                            <img class="direct-chat-img" src="/images/not-photo.jpg"
                                                alt="Message User Image">
                                        @endif

                                        <div class="direct-chat-text">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                @else
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-right">{{ $message->user->name }}</span>
                                            <span
                                                class="direct-chat-timestamp float-left">{{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:m:s') }}</span>
                                        </div>
                                        @if (isset($message->user->image))
                                            <img class="direct-chat-img"
                                                src="{{ asset('storage/' . $message->user->image) }}"
                                                alt="Message User Image">
                                        @else
                                            <img class="direct-chat-img" src="/images/not-photo.jpg"
                                                alt="Message User Image">
                                        @endif
                                        <div class="direct-chat-text">
                                            {{ $message->message }}
                                        </div>
                                    </div>
                                @endif
                            @empty
                                Digite uma mensagem para iniciar o direct chat.
                            @endforelse
                        </div>
                    </div>

                    <div class="card-footer">
                        <form id="ajaxForm">
                            @csrf
                            <div class="input-group">
                                <input type="hidden" name="direct_chat_id" id="direct_chat_id"
                                    value="{{ $directchat->id }}" />
                                <input type="text" name="message" id="message" placeholder="Mensagem"
                                    maxlength="255" class="form-control" />
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-info" id="saveBtn"
                                        value="create">Enviar</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Trabalho acadêmico</h3>
                    <div class="card-tools">
                    </div>
                </div>

                <div class="card-body">
                    @if (isset($job->file))
                        <i class="fas fa-paperclip"></i> {{ $job->file }}
                    @else
                        Aguardando envio do arquivo
                    @endif
                </div>

                <div class="card-footer">

                    <div class="d-flex justify-content-between">

                        @if (isset($job->id))
                            <form action="{{ route('admin.dashboard.update.workacademic', ['id' => $job->id]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <input type="hidden" name="course" value="{{ $course->slug }}" />
                                    <input type="hidden" name="discipline" value="{{ $discipline->slug }}" />
                                    <input type="hidden" name="user_id" value="{{ $student->id }}" />
                                    <input type="text" name="note"
                                        value="{{ number_format($job->note, 1, '.', '.') ?? 0 }}" placeholder="0.0"
                                        maxlength="4" class="form-control" required />
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Pontuar</button>
                                    </span>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('admin.dashboard.destroy.job', ['id' => $job->id]) }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="course" value="{{ $course->slug }}" />
                                <input type="hidden" name="discipline" value="{{ $discipline->slug }}" />
                                <input type="hidden" name="user_id" value="{{ $student->id }}" />
                                <div class="btn-group">
                                    <a href="{{ Storage::url($job->file) }}" target="_blank"
                                        class="btn btn-default ml-4"
                                        title="Clique aqui para baixar o arquivo acadêmico."><i
                                            class="fa fa-download"></i></a>
                                    <button type="submit" class="btn btn-default" onclick="return confirmaExcluir()">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
            </div>

        </div>

    </div>

@stop

@section('css')

@stop

@section('js')

    <script>
        function confirmaExcluir(value) {

            var conf = confirm("Deseja mesmo restaurar a avaliação? Os dados serão apagados.");

            if (conf) {
                return true;
            } else {
                return false;
            }
        }

        $(document).ready(function() {

            $('#message').keypress(function(event) {

                if (event.which == 13) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    event.preventDefault();

                    var _token = $("input[name='_token']").val();
                    var direct_chat_id = $("input[name='direct_chat_id']").val();
                    var message = $("input[name='message']").val();

                    if (message == '') {
                        $('#message').focus();
                        return;
                    }

                    // disabled the submit button
                    $("#saveBtn").prop("disabled", true);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('student.directchatmessage') }}",
                        data: {
                            _token: _token,
                            direct_chat_id: direct_chat_id,
                            message: message,
                        },
                        dataType: 'json',
                        success: function(response) {

                            $("#msg").html(response.success);

                            $("#saveBtn").prop("disabled", false);
                            $("#message").val('');

                            $.get("/student/char/message/" + direct_chat_id, function(
                                response) {
                                $("#direct-chat-messages").html(response);
                            });
                        },
                        error: function(err) {

                            console.log('Error:', err);
                            $("#saveBtn").prop("disabled", false);
                        }
                    });
                }
            });

            $('#saveBtn').click(function(event) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                event.preventDefault();

                var _token = $("input[name='_token']").val();
                var direct_chat_id = $("input[name='direct_chat_id']").val();
                var message = $("input[name='message']").val();

                if (message == '') {
                    $('#message').focus();
                    return;
                }

                // disabled the submit button
                $("#saveBtn").prop("disabled", true);

                $.ajax({
                    type: "POST",
                    url: "{{ route('student.directchatmessage') }}",
                    data: {
                        _token: _token,
                        direct_chat_id: direct_chat_id,
                        message: message,
                    },
                    dataType: 'json',
                    success: function(response) {

                        $("#msg").html(response.success);

                        $("#saveBtn").prop("disabled", false);
                        $("#message").val('');

                        $.get("/student/char/message/" + direct_chat_id, function(response) {
                            $("#direct-chat-messages").html(response);
                        });
                    },
                    error: function(err) {

                        console.log('Error:', err);
                        $("#saveBtn").prop("disabled", false);
                    }
                });
            });

        });
    </script>

    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@stop
