<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.profile.show') ? 'active' : '' }}" href="{{ route('admin.profile.show') }}">
        <span data-feather="user-check"></span>
        Perfil
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
        <span data-feather="users"></span>
        Usuarios
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
        <span data-feather="sliders"></span>
        Productos
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.exports*') ? 'active' : '' }}" href="{{ route('admin.exports.index') }}">
        <span data-feather="cloud-snow"></span>
        Exportaciones
    </a>
</li>
