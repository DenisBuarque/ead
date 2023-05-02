@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="width: 300px;">
        <form method="GET" action="{{ route('admin.directchats.index') }}">
            <div class="input-group input-group-md">
                <select name="search" class="form-control">
                    <option value="">Aluno</option>
                    @foreach ($users as $user)
                        @if ($search == $user->id)
                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                        @else
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>

                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">
                        <i class="fa fa-search mr-1"></i> Buscar
                    </button>
                </span>
            </div>
        </form>
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de direct chats criados</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Aluno(a)</th>
                        <th class="py-2">Disciplina</th>
                        <th class="py-2">Título</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($directchats as $chat)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($chat->user->image))
                                        <img src="{{ asset('storage/' . $chat->user->image) }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="{{ asset('images/not-photo.jpg') }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @endif
                                    <p style="line-height: 1; margin-bottom: 0">
                                        {{ $chat->user->name }}
                                        <br /><small>{{ $chat->user->phone . '  ' . $chat->user->email }}</small>
                                    </p>
                                </div>
                            </td>
                            <td class="py-2">
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $chat->discipline->title }}
                                    <br /><small>{{ $chat->course->title }}</small>
                                </p>
                            </td>
                            <td>{{ $chat->subject }}</td>
                            <td>
                                @if ($chat->active)
                                    Aberto
                                @else
                                    Encerrado
                                @endif
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($chat->created_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="#" class="btn btn-default btn-sm" data-toggle="modal"
                                        data-target="#modal-default{{ $chat->id }}" title="Vicualizar conversas">
                                        <i class="fas fa-comments"></i>
                                        <span
                                            style="position: absolute; top: -7px; left: 2px; width: 12px; height: 14px; border-radius: 3px; background-color: #28A745; color: #FFFFFF; padding: 0; font-size: 9px;">
                                            {{ $chat->direct_chat_messages->count() }}
                                        </span>
                                    </a>
                                    @can('chat-delete')
                                        <a href="{{ route('admin.directchat.destroy', ['id' => $chat->id]) }}"
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
                            <td class="py-2 text-center" colspan="8">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @foreach ($directchats as $chat)
                <div class="modal fade" id="modal-default{{ $chat->id }}" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Comentários Direct Chat</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>{{ $chat->subject }}</p>


                                <div class="direct-chat-messages" id="direct-chat-messages">

                                    @forelse ($directchatmessages->where('direct_chat_id', $chat->id) as $message)
                                        @if ($message->user_id == $chat->user_id)
                                            <div class="direct-chat-msg">
                                                <div class="direct-chat-infos clearfix">
                                                    <span
                                                        class="direct-chat-name float-left">{{ $message->user->name }}</span>
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
                                                    <span
                                                        class="direct-chat-name float-right">{{ $message->user->name }}</span>
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
                                        Nenhum mensagem encontrada.
                                    @endforelse
                                </div>

                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar
                                    Janela</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($directchats)
                <div class="mt-2 mx-2">
                    {{ $directchats->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif

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
