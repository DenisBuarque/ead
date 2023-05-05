<!-- Navbar -->
<nav class="bg-blue-800 border-gray-200 px-2 sm:px-4 py-2.5">
    <div class="container flex flex-wrap items-center justify-between mx-auto">
        <a href="/" class="flex items-center">
            <img src="/images/mini_logo.jpg" class="h-6 mr-3 sm:h-9" alt="Flowbite Logo" />
            <span class="self-center text-xl font-semibold whitespace-nowrap text-white">Setbal</span>
        </a>
        <div class="flex md:order-2">

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="text-white bg-blue-700 hover:bg-blue-800 hover:border-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0">Dashboard</a>
                @else
                    <a href="{{ route('access') }}"
                        class="text-white bg-blue-700 hover:bg-blue-800 hover:border-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-3 md:mr-0">Àrea
                        do Alunos</a>
                @endauth
            @endif

            <button data-collapse-toggle="navbar-cta" type="button"
                class="inline-flex items-center p-2 text-sm text-white rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                aria-controls="navbar-cta" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
            <ul
                class="flex flex-col p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-blue-800 md:dark:bg-gray-900">
                <li>
                    <a href="/"
                        class="block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-white md:p-0"
                        aria-current="page">Home</a>
                </li>
                <li>
                    <a href="/about"
                        class="block py-2 pl-3 pr-4 text-blue-700 md:text-white rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-200 md:p-0 md:dark:hover:text-white md:dark:hover:bg-transparent">Quem
                        Somos</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
