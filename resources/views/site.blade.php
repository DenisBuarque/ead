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

    @if (session('success'))
        <div class="flex p-4 m-2 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
            role="alert">
            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">Sucesso!</span> {{ session('success') }}
            </div>
        </div>
    @endif

    @include('partials.navbar')

    <div class="">
        <img src="/images/banner_ead.jpg" alt="image" class="w-full"/>
    </div>

    <!-- informations  -->
    <section class="grid grid-cols-1 p-2 md:p-0 md:grid-cols-3 gap-2 container my-10 m-auto">
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
            <svg class="w-16" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true">
                <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M8.34 1.804A1 1 0 019.32 1h1.36a1 1 0 01.98.804l.295 1.473c.497.144.971.342 1.416.587l1.25-.834a1 1 0 011.262.125l.962.962a1 1 0 01.125 1.262l-.834 1.25c.245.445.443.919.587 1.416l1.473.294a1 1 0 01.804.98v1.361a1 1 0 01-.804.98l-1.473.295a6.95 6.95 0 01-.587 1.416l.834 1.25a1 1 0 01-.125 1.262l-.962.962a1 1 0 01-1.262.125l-1.25-.834a6.953 6.953 0 01-1.416.587l-.294 1.473a1 1 0 01-.98.804H9.32a1 1 0 01-.98-.804l-.295-1.473a6.957 6.957 0 01-1.416-.587l-1.25.834a1 1 0 01-1.262-.125l-.962-.962a1 1 0 01-.125-1.262l.834-1.25a6.957 6.957 0 01-.587-1.416l-1.473-.294A1 1 0 011 10.68V9.32a1 1 0 01.804-.98l1.473-.295c.144-.497.342-.971.587-1.416l-.834-1.25a1 1 0 01.125-1.262l.962-.962A1 1 0 015.38 3.03l1.25.834a6.957 6.957 0 011.416-.587l.294-1.473zM13 10a3 3 0 11-6 0 3 3 0 016 0z">
                </path>
            </svg>
            <a href="#">
                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900">Gestor
                    administrativo</h5>
            </a>
            <p class="mb-3 font-normal text-gray-500">Uma área administrativa para que o aluno possa
                gerenciar seus estudos, assitir vídeos, baixar arquivos, conversar com alunos.</p>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">

            <svg class="w-16" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true">
                <path
                    d="M3.505 2.365A41.369 41.369 0 019 2c1.863 0 3.697.124 5.495.365 1.247.167 2.18 1.108 2.435 2.268a4.45 4.45 0 00-.577-.069 43.141 43.141 0 00-4.706 0C9.229 4.696 7.5 6.727 7.5 8.998v2.24c0 1.413.67 2.735 1.76 3.562l-2.98 2.98A.75.75 0 015 17.25v-3.443c-.501-.048-1-.106-1.495-.172C2.033 13.438 1 12.162 1 10.72V5.28c0-1.441 1.033-2.717 2.505-2.914z">
                </path>
                <path
                    d="M14 6c-.762 0-1.52.02-2.271.062C10.157 6.148 9 7.472 9 8.998v2.24c0 1.519 1.147 2.839 2.71 2.935.214.013.428.024.642.034.2.009.385.09.518.224l2.35 2.35a.75.75 0 001.28-.531v-2.07c1.453-.195 2.5-1.463 2.5-2.915V8.998c0-1.526-1.157-2.85-2.729-2.936A41.645 41.645 0 0014 6z">
                </path>
            </svg>
            <a href="#">
                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900">Forum de
                    discursões
                </h5>
            </a>
            <p class="mb-3 font-normal text-gray-500">Aumente seu conhecimento participando com
                professores e alunos dos forums de discursões de cada disciplina.</p>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
            <svg class="w-16" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true">
                <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z">
                </path>
            </svg>

            <a href="#">
                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900">Avaliação on-line
                </h5>
            </a>
            <p class="mb-3 font-normal text-gray-500">Após conclusão de cada disciplina realizer um
                teste on-line com questões de multiplas escolhas e questões abertas.</p>
        </div>
    </section>

    <!-- Caontainer mobile -->
    <section class="container m-auto my-20 p-3 md:flex md:p-0">
        <div class="block md:flex-1">
            <img src="images/mobile.jpg" alt="Mobile" class="w-full" />
        </div>
        <div class="block md:flex-1">
            <h1 class="text-4xl mb-5 text-blue-700">De qualquer dispositivo</h1>
            <p class="text-xl text-gray-500">No notebook, tablet ou seu smartphone, acesse nossa plataforma digital e
                estude do conforto de sua casa, faça seu próprio horário.</p>
            <ul class="mt-5 ml-10 list-disc">
                <li>Estude qualquer lugar;</li>
                <li>Crie seus proprios horários;</li>
                <li>Administre seu estudos;</li>
                <li>Converse com alunos;</li>
            </ul>
        </div>
    </section>

    <!-- Container Courses -->
    <section class="container mt-10 mb-20 m-auto p-3 md:p-0">

        <h1 class="text-center text-4xl text-blue-700">Conheça nossos cursos</h1>
        <p class="text-center text-2xl mb-5">Presencial ou a distancia</p>

        <div class="grid grid-cols-1 ms:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-5">
            @foreach ($courses as $course)
                <div class="bg-white border border-gray-200 rounded-lg shadow">
                    <a href="#">
                        <img class="rounded-t-lg w-full" src="{{ asset('storage/' . $course->image) }}"
                            alt="Image" />
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
                        <br />
                        <a href="{{ route('course.show', ['slug' => $course->slug]) }}"
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
