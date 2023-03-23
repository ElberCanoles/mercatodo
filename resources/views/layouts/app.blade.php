<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>

<body>

    <main>
        <div class="container py-4">
            <header class="pb-3 mb-4 border-bottom">
                <a href="{{ route('home') }}" class="d-flex align-items-center text-dark text-decoration-none">
                    <span class="fs-4">Mercatodo</span>
                </a>
                @if (Route::has('login'))
                    <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>

                            <a href="javascript:" class="btn btn-bg-light btn-color-gray-600 btn-active-color-gray w-100"
                                data-bs-toggle="tooltip" data-bs-trigger="hover"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <span class="btn-label">Cerrar sesión</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Acceder</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Registrate</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </header>

            <div id="app">
                @yield('content')
            </div>


            <footer class="pt-3 mt-4 text-muted border-top">
                &copy; {{ date('Y') }}
            </footer>
        </div>
    </main>
    @stack('scripts')
</body>

</html>
