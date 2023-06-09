<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('buyer.profile.show') ? 'active' : '' }}" href="{{ route('buyer.profile.show') }}">
        <span data-feather="user-check"></span>
        Perfil
    </a>
    <a class="nav-link {{ request()->routeIs('buyer.orders.index') ? 'active' : '' }}" href="{{ route('buyer.orders.index') }}">
        <span data-feather="user-check"></span>
        Mis Ordenes
    </a>
</li>
