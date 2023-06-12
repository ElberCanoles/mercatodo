<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>

    <main>
        <div class="container py-4">

            <nav class="navbar navbar-expand-lg navbar-dark" aria-label="Ninth navbar example">
                <div class="container-xl">
                    <a class="navbar-brand" href="{{ route('home') }}">Merca Todo</a>

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">Productos</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('buyer.cart.index') ? 'active' : '' }}" href="{{ route('buyer.cart.index') }}">Ver Carrito</a>
                        </li>
                    </ul>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#mainNavbarCollapsable" aria-controls="mainNavbarCollapsable" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="mainNavbarCollapsable">

                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            @if (Route::has('login'))

                                @auth
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}"
                                            href="{{ App\Services\Auth\EntryPoint::resolveRedirectRoute() }}">Dashboard</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="javascript:"
                                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                            <span class="btn-label">Cerrar sesión</span>
                                        </a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('login') ? 'active' : '' }}"
                                            href="{{ route('login') }}">Acceder</a>
                                    </li>

                                    @if (Route::has('register'))
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->is('register') ? 'active' : '' }}"
                                                href="{{ route('register') }}">Registrate</a>
                                        </li>
                                    @endif
                                @endauth

                            @endif
                        </ul>

                    </div>
                </div>
            </nav>

            <div id="app">
                @yield('content')
            </div>


            <footer class="pt-3 mt-4 text-muted border-top">
                &copy; Bootcamp Evertec {{ date('Y') }}
            </footer>
        </div>
    </main>
    @stack('scripts')
</body>

</html>
