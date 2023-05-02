<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Seminário Teologico Batista de Alagoas - Setbal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    @include('partials.navbar')

    <section class="container m-auto my-5 mt-10 mb-16 p-3 md:p-0">

        <ol class="items-center sm:flex">
            <li class="relative mb-6 sm:mb-0">
                <div class="flex items-center">
                    <div
                        class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white sm:ring-8 shrink-0">
                        <svg aria-hidden="true" class="w-3 h-3 text-blue-800" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="hidden sm:flex w-full bg-gray-200 h-0.5"></div>
                </div>
                <div class="mt-3 sm:pr-8">
                    <h3 class="text-lg font-semibold text-gray-900">1º Passo:</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400">Informações do curso</time>
                    <p class="text-base font-normal text-gray-500">Leia atentamente as informações na descrição do curso para que não fique dúvidas.</p>
                </div>
            </li>
            <li class="relative mb-6 sm:mb-0">
                <div class="flex items-center">
                    <div
                        class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white sm:ring-8 shrink-0">
                        <svg aria-hidden="true" class="w-3 h-3 text-blue-800" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="hidden sm:flex w-full bg-gray-200 h-0.5"></div>
                </div>
                <div class="mt-3 sm:pr-8">
                    <h3 class="text-lg font-semibold text-gray-900">2º Passo:</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400">Cadastre-se</time>
                    <p class="text-base font-normal text-gray-500">Preencha corretamente seus dados no formulario de solicitação de sua pré matricula.</p>
                </div>
            </li>
            <li class="relative mb-6 sm:mb-0">
                <div class="flex items-center">
                    <div
                        class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white sm:ring-8 shrink-0">
                        <svg aria-hidden="true" class="w-3 h-3 text-blue-800" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="hidden sm:flex w-full bg-gray-200 h-0.5"></div>
                </div>
                <div class="mt-3 sm:pr-8">
                    <h3 class="text-lg font-semibold text-gray-900">3º Passo:</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400">Aguarde nosso contato</time>
                    <p class="text-base font-normal text-gray-500">Apos suas solicitação de pré matricula ser enviado para nossa equipe avaliadora, aguarde nosso contato.</p>
                </div>
            </li>
        </ol>

    </section>

    <section class="container m-auto my-10 p-3 md:p-0 md:flex">

        <div class="block md:mr-5 md:flex-1">

            <div class="flex mb-5">

                @if (isset($course->image))
                    <img class="rounded-full w-20 h-20" src="{{ asset('storage/' . $course->image) }}" alt="image description">
                @else
                    <div class="w-20 h-20 rounded-full bg-gray-300"></div>
                @endif

                <div class="pl-5">
                    <h1 class="text-3xl font-medium text-blue-700">{{ $course->title }}</h1>
                    <label class="block mb-5">
                        @if ($course->institution == 'setbal')
                            Presencial
                        @else
                            EAD
                        @endif 
                        / {{ $course->duration }}
                    </label>
                </div>
            </div>

            <div>{!! $course->description !!}</div>

            <div class="relative mt-10 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Disciplina
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Ano
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Carga Horária
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($course->disciplines->sortBy('year') as $discipline)
                            <tr class="bg-white border-b">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $discipline->title }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $discipline->year }}º ano
                                </td>
                                <td class="px-6 py-4">
                                    {{ $discipline->workload }} hrs
                                </td>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="block md:ml-5 mt-5 md:mt-0 md:flex-1">
            <h1 class="text-3xl font-medium text-blue-700">Pré Matricula</h1>
            <label class="block mb-5">Informe seus dados e faça sua pré matricula.</label>

            <form method="POST" action="{{ route('course.preregistration') }}">
                @csrf
                @method('POST')
                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                <input type="hidden" name="slug" value="{{ $course->slug }}"/>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="first_name"
                            class="block mb-2 text-sm font-medium text-gray-900">Nome completo</label>
                        <input type="text" name="name" id="first_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="John Richard Nuelly" required maxlength="100" />
                    </div>
                    <div>
                        <label for="last_name"
                            class="block mb-2 text-sm font-medium text-gray-900">Telefone para
                            contato</label>
                        <input type="tel" name="phone" id="phone"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="(82)90000-0000" required maxlength="16"/>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Endereço de E-Mail</label>
                    <input type="email"  name="email" id="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="john.doe@company.com" required  maxlength="100"/>
                </div>

                <div class="flex items-center mb-6">
                    <input id="link-radio" type="radio" value=""
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                    <label for="link-radio" class="ml-2 text-sm font-medium text-gray-900">Li e
                        concordo com os <a href="#"
                            class="text-blue-600 hover:underline">termos
                            de serviço</a> e <a href="#"
                            class="text-blue-600 hover:underline">politicas de privacidade</a>
                        oferecido pelo instituição.</label>
                </div>

                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Solicitar
                    Pré-matricula</button>
            </form>

        </div>
    </section>

    
    
    <!-- Container Courses -->
    <section class="container my-10 m-auto p-3 md:p-0">

        <h1 class="text-center text-4xl text-blue-700">Conheça outros cursos </h1>
        <p class="text-center text-2xl mb-5">Presencial ou a distancia</p>

        <div class="grid grid-cols-1 ms:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-5">
            @foreach ($courses as $course)
                <div class="bg-white border border-gray-200 rounded-lg shadow">
                    <a href="#">
                        <img class="rounded-t-lg w-full" src="{{ asset('storage/' . $course->image)}}" alt="Image" />
                    </a>
                    <div class="p-5">
                        <a href="/show">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $course->title }}</h5>
                        </a>
                        <div class="flex justify-between">
                            @if ($course->institution == 'setbal')
                                <small>Presencial</small>
                            @else
                                <small>EAD</small>
                            @endif
                            <small>{{ $course->duration }}</small>
                        </div>

                        <p class="mb-3 font-normal text-gray-700">
                            {!! \Illuminate\Support\Str::substr($course->description, 0, 100) !!}
                        </p>
                        <br/>
                        <a href="{{ route('course.show',['slug' => $course->slug]) }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                            Mais informações
                            <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @include('partials.footer')

</body>

</html>
