@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1 class="m-0">Avaliação questões multiplas escolhas <strong>{{ $course->title }}</strong></h1>
        <a href="{{ route('classroom', ['slug' => $course->slug]) }}" class="btn btn-md btn-danger"
            title="Sair da sala de aula.">
            <i class="fa fa-times mr-1"></i> Sair
        </a>
    </div>
@stop

@section('content')

    <form method="POST" action="{{ route('classroom.store.multiplequestion') }}" class="mt-4">
        @csrf
        <input type="hidden" name="slug" value="{{ $course->slug }}" />
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="user-block">
                    @if (isset(auth()->user()->name))
                        <img class="img-circle" src="{{ asset('storage/' . auth()->user()->image) }}" alt="User Image">
                    @else
                        <img class="img-circle" src="/images/not-photo.jpg" alt="User Image">
                    @endif

                    <span class="username"><a href="#">{{ auth()->user()->name }}</a></span>
                    <span class="description">
                        <i>Atenção: você tem <b>
                                <span id="minuto">00m</span><span id="segundo">00s</span>
                            </b> para finalizar sua avaliação. Boa sorte!</i>
                    </span>
                </div>

                <br /><br /><br />

                @foreach ($multiplequestions->random(10) as $question)
                    <input type="hidden" name="course_id" value="{{ $course->id }}" />
                    <input type="hidden" name="discipline_id" value="{{ $discipline->id }}" />
                    <input type="hidden" name="multiple_question_id[]" value="{{ $question->id }}" />
                    <input type="hidden" name="gbt[]" value="{{ $question->gabarito }}" />
                    <strong class="d-block mb-2">{{ $question->title }}</strong>
                    <input type="radio" name="option[{{ $question->id }}]" value="1" />
                    {{ $question->response_one }}<br />
                    <input type="radio" name="option[{{ $question->id }}]" value="2" />
                    {{ $question->response_two }}<br />
                    <input type="radio" name="option[{{ $question->id }}]" value="3" />
                    {{ $question->response_tree }}<br />
                    <input type="radio" name="option[{{ $question->id }}]" value="4" />
                    {{ $question->response_four }}
                    <hr />
                @endforeach

                <button type="submit" class="btn btn-info float-right" onclick="valida()">Enviar
                    respostas</button>
                <br /><br /><br /><br />
            </div>
            <div class="col-md-1"></div>
        </div>
    </form>

@stop

@section('css')

@stop

@section('js')
    <script>
        
        var intervalo;

        function tempo(op) {
            var s = 0;
            var m = 60;

            intervalo = window.setInterval(function() {
                if (s == 0) {
                    m--;
                    s = 60;
                }
                if (m == 60) {
                    h--;
                    s = 0;
                    m = 0;
                }
                if (s < 10) {
                    document.getElementById("segundo").innerHTML = "0" + s + "s";
                } else {
                    document.getElementById("segundo").innerHTML = s + "s";
                }
                if (m < 10) {
                    document.getElementById("minuto").innerHTML = "0" + m + "m";
                } else {
                    document.getElementById("minuto").innerHTML = m + "m";
                }
                s--;

                if (m == 0) {
                    alert("Seu tempo de avaliação encerrou.");
                    parar();
                }

            }, 1000);
        }

        function parar() {
            window.clearInterval(intervalo);
            window.location.href = "{{ url('/classroom/' . $course->slug) }}";
        }

        function confirmSend() {
            var conf = confirm(
                "Deseja mesmo enviar sua avaliação.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }

        window.onload = tempo;

        /*
        Verificar:
            se o usuário atualizou a página.
            se o usuário mudou de página.
            se o usuário respondeu todas as peguntas
        */
        

        /*$(document).ready(function() {
            window.addEventListener('blur', function() {
                alert("Valiação Encerreda, você mudou de página.");
                window.location.href = "{{ url('/classroom/' . $course->slug) }}";
            });
        });*/

        /*
        function radio_preenchido(nome) {
            var opcoes = document.getElementsByName(nome);
            for (var i = 0; i < 10; i++) {
                if (!opcoes[i].checked) {
                    return true;
                }
            }
            return false;
        };

        function checar() {
            if (radio_preenchido('option')) {
                alert('Responda a pergunta');
                return false;
            }

            return true;
        };
        */
    </script>
@stop
