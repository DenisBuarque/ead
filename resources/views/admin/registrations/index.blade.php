@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <form method="GET" action="{{ route('admin.registrations.index') }}" onsubmit="return handleSubmit()">
                <div class="input-group input-group-md">
                    <select name="course" class="form-control mr-1">
                        <option value="">Curso</option>
                        @foreach ($courses as $course)
                            @if (isset($search_course) && $search_course == $course->id)
                                <option value="{{ $course->id }}" selected>{{ $course->title }}
                                    ({{ $course->institution }})
                                </option>
                            @else
                                <option value="{{ $course->id }}">{{ $course->title }} ({{ $course->institution }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <select name="payment" class="form-control mr-1">
                        <option value="">Pagamento</option>
                        <option value="yes" @if ($search_payment == 'yes') selected @endif>Confirmado</option>
                        <option value="no" @if ($search_payment == 'no') selected @endif>Pendente</option>
                    </select>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            </form>
        </div>
        @can('registration-create')
            <a href="{{ route('admin.registration.create') }}" class="btn btn-md btn-info" title="Adicionar novo registro">
                <i class="fa fa-plus mr-1"></i> Adicionar novo
            </a>
        @endcan
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
                    <p>Total de matrículas</p>
                </div>
                <div class="icon">
                    <i class="fa fa-trophy"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $records->where('payment', 'yes')->count() }}</h3>
                    <p>Matrículas confirmadas</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-up"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $records->where('payment', 'no')->count() }}</h3>
                    <p>Matrículas pendentes</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-down"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ count($courses) }}</h3>
                    <p>Cursos disponiveis</p>
                </div>
                <div class="icon">
                    <i class="fa fa-graduation-cap"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de matrículas:</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="py-2">Aluno(a)</th>
                        <th class="py-2">Curso</th>
                        <th class="py-2 text-center">Pagamento</th>
                        <th class="py-2">Criado</th>
                        <th class="py-2">Atualizado</th>
                        <th class="py-2" style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registrations as $registration)
                        <tr>
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    @if (isset($registration->user->image))
                                        <img src="{{ asset('storage/' . $registration->user->image) }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @else
                                        <img src="{{ asset('images/not-photo.jpg') }}" alt="Photo"
                                            style="width: 32px; height: 32px;" class="img-circle img-size-32 mr-2">
                                    @endif
                                    <p style="line-height: 1; margin-bottom: 0">
                                        {{ $registration->user->name }}
                                        <br /><small>{{ $registration->user->phone . '  ' . $registration->user->email }}</small>
                                    </p>
                                </div>
                            </td>
                            <td class="py-2">
                                <p style="line-height: 1; margin-bottom: 0">
                                    {{ $registration->course->title }}
                                    <br /><small>{{ $registration->course->institution }} -
                                        {{ $registration->course->duration }}</small>
                                </p>
                            </td>
                            <td class="py-2 text-center">
                                @if ($registration->payment == 'yes')
                                    <small class="badge badge-success">Confirmado</small>
                                @else
                                    <small class="badge badge-danger p-1 px-2">Pendente</small>
                                @endif
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($registration->created_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($registration->updated_at)->format('d/m/Y H:m:s') }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    @can('registration-edit')
                                        <a href="{{ route('admin.registration.edit', ['id' => $registration->id]) }}"
                                            class="btn btn-default btn-sm" title="Editar registro">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('registration-delete')
                                        <a href="{{ route('admin.registration.destroy', ['id' => $registration->id]) }}"
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
                            <td class="py-2 text-center" colspan="6">
                                <span>Nenhum registro cadastrado.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($registrations)
                <div class="mt-2 mx-2">
                    {{ $registrations->withQueryString()->links('pagination::bootstrap-5') }}
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
