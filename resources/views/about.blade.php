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

    <section class="container m-auto my-10 p-3 md:p-0 md:flex">

        <div class="block md:mr-5 md:flex-1">

            <h1 class="text-3xl font-medium">Quem Somos</h1>
            <label class="block mb-5">Seminário Teológico Batista de Alagoas</label>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut, quam. Nemo pariatur doloribus molestias!
                Expedita recusandae delectus aut. Necessitatibus animi consectetur modi aliquid aliquam perferendis
                ducimus
                enim numquam. Non, quas. Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut, quam. Nemo
                pariatur doloribus molestias!
                Expedita recusandae delectus aut. Necessitatibus animi consectetur modi aliquid aliquam perferendis
                ducimus
                enim numquam. Non, quas.</p>

            <br />
            <ol class="relative border-l border-gray-200">
                @foreach ($polos as $polo)
                    <li class="mb-10 ml-4">
                        <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white">
                        </div>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400">Polo</time>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $polo->title }}</h3>
                        <p class="mb-4 text-base font-normal text-gray-500">
                            {{ $polo->phone . ' ' . $polo->email }}<br/>
                            {{ $polo->address . ', ' . $polo->city . ' ' . $polo->state }}
                        </p>
                    </li>
                @endforeach
            </ol>

        </div>
        <div class="block md:ml-5 mt-5 md:mt-0 md:flex-1">
            <h1 class="text-3xl font-medium">Equipe</h1>
            <label class="block mb-5">Conheça quem partecipa e acredita em nossas ideias.</label>

            <div class="grid grid-cols-2 gap-2">

                @for ($i = 0; $i < 2; $i++)
                    <div>
                        <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow">
                            <div class="flex justify-end px-4 pt-4">

                            </div>
                            <div class="flex flex-col items-center pb-10">
                                <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="images/dev.jpg"
                                    alt="Bonnie image" />
                                <h5 class="mb-1 text-xl font-medium text-gray-900">Bonnie Green</h5>
                                <span class="text-sm text-gray-500">Visual Designer</span>
                                <div class="flex mt-4 space-x-3 md:mt-6">
                                    <a href="#"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                                clip-rule="evenodd" />
                                        </svg></a>
                                    <a href="#"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                                clip-rule="evenodd" />
                                        </svg></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor

            </div>

        </div>
    </section>


    @include('partials.footer')

</body>

</html>
