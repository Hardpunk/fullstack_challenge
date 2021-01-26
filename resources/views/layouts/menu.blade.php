<li class="nav-item {{ Request::is('users*') ? 'menu-is-opening menu-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-user"></i>
        <p>Usuários <i class="fas fa-angle-left right"></i></p>
    </a>
    <ul class="nav nav-treeview" style="display: {{ Request::is('users*') ? 'block' : 'hidden' }};">
        <li class="nav-item">
            <a href="{!! route('users.index') !!}"
                class="nav-link {{ Request::is('users') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Listar</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{!! route('users.create') !!}"
                class="nav-link {{ Request::is('users/create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Novo</p>
            </a>
        </li>
    </ul>
</li>
