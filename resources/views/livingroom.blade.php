@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1 class="m-0">Sala de Estudo Digital <strong>{{ $discipline->title }}</strong></h1>
        <a href="/classroom/{{ $course->slug }}" class="btn btn-md btn-danger" title="Sair da sala de aula.">
            <i class="fa fa-times mr-1"></i> Sair da Sala
        </a>
    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Modulos de envino</span>
                    <span class="info-box-number">{{ $disciplinemodules->where('category', 'module')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fa fa-film"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Vídeos</span>
                    <span class="info-box-number">
                        {{ $disciplinemodules->where('category', 'movie')->count() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fa fa-download"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Arquivos para download</span>
                    <span class="info-box-number">{{ $disciplinemodules->where('category', 'file')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fa fa-calendar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Mural informativo</span>
                    <span class="info-box-number">{{ $murals->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <strong>Módulos de ensino</strong>
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover">
                        <tbody>
                            @forelse ($disciplinemodules->where('category','module') as $module)
                                <tr>
                                    <td class="d-flex">
                                        <a href="#" onClick="showModule({{ $module->id }})">{{ $module->title }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center">Nenhum módulo disponível</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="col-md-6">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Clique sobre o módulo de ensino para ver seu conteúdo.<strong></strong></h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div id="contentModule">

                    </div>
                </div>
                <div class="card-footer"></div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Arquivos para download</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <tbody>
                            @forelse ($disciplinemodules->where('category','file') as $module)
                                <tr>
                                    <td>
                                        <small class="d-block mb-3">{{ $module->description }}</small>
                                        <a href="{{ Storage::url($module->file) }}" target="_blank"
                                            class="btn btn-sm bg-info">Download Arquivo</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center">Nenhum arquivo disponível para download</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="timeline">
                <div class="time-label">
                    <span class="bg-info">Vídeos</span>
                </div>
                @forelse ($disciplinemodules->where('category','movie') as $module)
                    <div>
                        <i class="fas fa-video bg-info"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i>
                                {{ \Carbon\Carbon::parse($module->created_at)->format('d/m/Y H:m:s') }}</span>
                            <h3 class="timeline-header"><label>{{ $module->title }}</label></h3>
                            <div class="timeline-body">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item"
                                        src="https://www.youtube.com/embed/{{ $module->movie }}"
                                        allowfullscreen=""></iframe>
                                </div>
                            </div>
                            <div class="timeline-footer">
                                <span>{{ $module->description }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div>
                        <i class="fas fa-video bg-maroon"></i>
                        <div class="timeline-item">
                            <div class="timeline-body text-center">
                                Nenhum vídeo disponível
                            </div>
                        </div>
                    </div>
                @endforelse

                <div>
                    <i class="fas fa-clock bg-gray"></i>
                </div>
            </div>

        </div>
        <div class="col-md-3">

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

            @if (!$directchat)

                <div class="card card-info card-outline direct-chat direct-chat-primary">
                    <div class="card-header">
                        <h3 class="card-title">Direct Chat</h3>
                        <div class="card-tools"></div>
                    </div>
                    <div class="card-body p-4">
                        <p>Digite o assunto do chat para iniciar uma conversa:</p>
                    </div>
                    <div class="card-footer">
                        <form action="{{ route('livingroom.start.directchat') }}" method="post">
                            @csrf
                            <div class="input-group">
                                <input type="hidden" name="course_slug" value="{{ $course->slug }}" />
                                <input type="hidden" name="discipline_slug" value="{{ $discipline->slug }}" />
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
                <div class="card card-info card-outline direct-chat direct-chat-info">
                    <div class="card-header">
                        <h3 class="card-title">Direct Chat <strong id="msg"></strong></h3>
                        <div class="card-tools"></div><br/>
                        <small>{{ $directchat->subject }}</small>
                    </div>
                    <div class="card-body">
                        <div class="direct-chat-messages" id="direct-chat-messages">

                            @foreach ($directchat->direct_chat_messages as $message)
                                @if ($message->user_id == auth()->user()->id)
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
                            @endforeach

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
    <script>
        function showModule(id) {

            if (id == "") {
                document.getElementById("contentModule").innerHTML = "";
                return;
            }
            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("contentModule").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "/livingroom/discipline/module/" + id, true);
            xmlhttp.send();
        }

        /*function listComments(discipline, user) {

            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("direct-chat-messages").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "/livingroom/chat/" + discipline + '/' + user, true);
            xmlhttp.send();
        }*/

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
                        url: "{{ route('livingroom.directchatmessage') }}",
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

                            $.get("/livingroom/char/message/" + direct_chat_id, function(
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
                    url: "{{ route('livingroom.directchatmessage') }}",
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

                        $.get("/livingroom/char/message/" + direct_chat_id, function(response) {
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
