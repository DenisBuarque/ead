@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1 class="m-0">Resultado Avaliação disciplina <strong>{{ $discipline->title }}</strong></h1>
        <a href="{{ route('classroom', ['slug' => $course->slug]) }}" class="btn btn-md btn-danger"
            title="Sair da sala de aula.">
            <i class="fa fa-times mr-1"></i> Sair
        </a>
    </div>
@stop

@section('content')


    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">

                        <div class="user-block">
                            @if (isset(auth()->user()->name))
                                <img class="img-circle" src="{{ asset('storage/' . auth()->user()->image) }}"
                                    alt="User Image">
                            @else
                                <img class="img-circle" src="/images/not-photo.jpg" alt="User Image">
                            @endif

                            <span class="username"><a href="#">{{ auth()->user()->name }}</a></span>
                            <span class="description">
                                <i>Confira o resulta da sua avaliação de questões multiplas</i>
                            </span>
                        </div>

                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Resulta do sua avaliação acertos e erros:</th>
                                <th>Resposta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resultmultiples as $result)
                                <tr>
                                    <td>{{ $result->multiple_question->title }}</td>
                                    <td>
                                        @if ($result->gabarito == $result->option)
                                            <span class="badge badge-success px-3">Acertou</span>
                                        @else
                                            <span class="badge badge-danger px-4">Errou</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="col-md-1"></div>
    </div>

@stop

@section('css')

@stop

@section('js')
    <script>
        
    </script>
@stop
