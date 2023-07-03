<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    @vite(['resources/css/app.css', 'resources/sass/gallery.scss', 'resources/css/template/dashboard.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

<header class="navbar navbar-dark sticky-top flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('home') }}">{{ auth()->user()->name }}</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="javascript:"
               onclick="event.preventDefault();
               document.getElementById('logout-form').submit();">Cerrar Sesión</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}" aria-current="page"
                           href="{{ \App\Domain\Users\Services\EntryPoint::resolveRedirectRoute() }}">
                            <span data-feather="home"></span>
                            Dashboard
                        </a>
                    </li>

                    @role(App\Enums\User\RoleType::ADMINISTRATOR)
                    @include('layouts.partials.admin-menu-navigation')
                    @endrole

                    @role(App\Enums\User\RoleType::BUYER)
                    @include('layouts.partials.buyer-menu-navigation')
                    @endrole

                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

            <div id="app">

                @yield('content')

            </div>

        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
