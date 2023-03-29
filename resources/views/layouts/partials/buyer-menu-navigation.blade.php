<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('buyer.profile.show') ? 'active' : '' }}" href="{{ route('buyer.profile.show') }}">
        <span data-feather="user-check"></span>
        Perfil
    </a>
</li>
