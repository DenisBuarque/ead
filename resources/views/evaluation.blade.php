@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1 class="m-0">Sala de Valaliação <strong>{{ $course->title }}</strong></h1>
        <a href="{{ route('classroom', ['slug' => $course->slug]) }}" class="btn btn-md btn-danger"
            title="Sair da sala de aula.">
            <i class="fa fa-times mr-1"></i> Sair da Sala
        </a>
    </div>
@stop

@section('content')

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
        <div class="col-md-4">
            <div class="card card-widget widget-user">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">Questões</h3>
                    <h5 class="widget-user-desc">Multiplas escolhas</h5>
                </div>
                <div class="widget-user-image">
                    <img class="img-circle elevation-2" src="/images/logo.jpg" style="width: 85px; height: 85px"
                        alt="Logo">
                </div>
                <div class="card-footer">
                    <p>Inicie sua avaliação, sortearemos 10 questões de multiplas escolhas para avaliar seus conhecimentos
                        adiquirido no curso, após iniciar sua avaliação.</p>
                    <ul>
                        <li>Você tem 1 hora para finalizar;</li>
                        <li>
                            @if ($access_multiple == 0)
                                Você tem 3 acessos disponíveis.
                            @elseif ($access_multiple < 4)
                                Você acessou <b>{{ $access_multiple }} vez(es) de 3 acessos</b> disponíveis.
                            @endif
                        </li>
                    </ul>
                    @if ($verify_multiple_question == 0)
                        @if ($access_multiple < 3)
                            <a href="{{ route('classroom.livingroom.multiplequestion', ['course' => $course->slug, 'discipline' => $discipline->slug]) }}"
                                class="btn btn-info d-block">Iniciar Avaliação</a>
                        @else
                            <a class="btn btn-default d-block">Limite de acesso esgotado</a>
                        @endif
                    @else
                        <a class="btn btn-default d-block">Avaliação finalizada</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-widget widget-user">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">Perguntas Abertas</h3>
                    <h5 class="widget-user-desc">Digite sua resposta</h5>
                </div>
                <div class="widget-user-image">
                    <img class="img-circle elevation-2" src="/images/logo.jpg" style="width: 85px; height: 85px"
                        alt="Logo">
                </div>
                <div class="card-footer">
                    <p>Inicie sua avaliação, sortearemos duas perguntas para você responder com seus conhecimentos
                        adiquirido no curso, após iniciar sua avaliação:</p>
                    <ul>
                        <li>Você tem 1 hora para finalizar;</li>
                        <li>
                            @if ($access_open == 0)
                                Você tem 3 acessos disponíveis.
                            @elseif ($access_open < 4)
                                Você acessou <b>{{ $access_open }} vez(es) de 3 acessos</b> disponíveis.
                            @endif
                        </li>
                    </ul>
                    @if ($verify_open_question == 0)
                        @if ($access_open < 3)
                            <a href="{{ route('classroom.openquestion', ['course' => $course->slug, 'discipline' => $discipline->slug]) }}"
                                class="btn btn-info d-block">Iniciar Avaliação</a>
                        @else
                            <a class="btn btn-default d-block">Limite de acesso esgotado</a>
                        @endif
                    @else
                        <a class="btn btn-default d-block">
                            Avaliação finalizada
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-widget widget-user">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">Anexo Arquivo</h3>
                    <h5 class="widget-user-desc">Trabalho acadêmico</h5>
                </div>
                <div class="widget-user-image">
                    <img class="img-circle elevation-2" src="/images/logo.jpg" style="width: 85px; height: 85px"
                        alt="Logo">
                </div>
                <div class="card-footer">
                    <p>Coloque a disposição do professor seu trabalho acadêmico obrigatório do aluno, envie somente arquivo
                        no formato pedido pela plataforma.</p>
                    <ul>
                        <li>Arquivo no format .pdf, .doc ou .docx;</li>
                        <li>Tamanho maxímo do arquivo 10 MB;</li>
                    </ul>
                    @if ($verify_file == 0)
                        <a href="" class="btn btn-info d-block" data-toggle="modal" data-target="#modal-default">
                            Anexar Arquivo pdf
                        </a>
                    @else
                        <a class="btn btn-default d-block">
                            Arquivo enviado
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('classroom.storejob') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="course" value="{{ $course->slug }}" />
                <input type="hidden" name="discipline" value="{{ $discipline->slug }}" />
                <input type="hidden" name="course_id" value="{{ $course->id }}" />
                <input type="hidden" name="discipline_id" value="{{ $discipline->id }}" />
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Anexar arquivo trabalho acadêmico</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Adicione arquivo no formato .pfd ou .doc e clique em salvar.</p>
                        <p><input type="file" name="file" /></p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar Janela</button>
                        <button type="submit" class="btn btn-primary">Enviar arquivo</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

@stop

@section('css')

@stop

@section('js')
    <script></script>
@stop
