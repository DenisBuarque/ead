@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1 class="m-0">Avaliação questões abertas <strong>{{ $course->title }}</strong></h1>
        <a href="{{ route('classroom', ['slug' => $course->slug]) }}" class="btn btn-md btn-danger"
            title="Sair da sala de aula.">
            <i class="fa fa-times mr-1"></i> Sair
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



    <form method="POST" action="{{ route('classroom.openresponse') }}" class="mt-4">
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

                <br />

                <br /><br />
                @foreach ($openquestions->random(2) as $question)
                    <input type="hidden" name="course_id" value="{{ $course->id }}" />
                    <input type="hidden" name="discipline_id" value="{{ $discipline->id }}" />
                    <input type="hidden" name="open_question_id[]" value="{{ $question->id }}" />
                    <p>{{ $question->title }}</p>
                    <textarea name="resposta[]" style="width: 100%"></textarea>
                    <br /><br />
                @endforeach
                <button type="submit" class="btn btn-info float-right" onclick="return confirmSend()">Enviar
                    respostas</button>
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

        /*$(document).ready(function() {
            window.addEventListener('blur', function() {
                alert("Valiação Encerreda, você mudou de página.");
                window.location.href = "{{ url('/classroom/' . $course->slug) }}";
            });
        });*/
    </script>
@stop
