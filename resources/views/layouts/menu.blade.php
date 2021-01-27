@if($user->is_admin)
<li class="nav-item {{ Request::is('users*') ? 'menu-is-opening menu-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-user"></i>
        <p>Usuários <i class="fas fa-angle-left right"></i></p>
    </a>
    <ul class="nav nav-treeview" style="display: {{ Request::is('users*') ? 'block' : 'hidden' }};">
        <li class="nav-item">
            <a href="{!! route('users.index') !!}"
                class="nav-link {{ Request::is('users') ? 'active' : '' }}">
                <i class="{{ Request::is('users') ? 'fas' : 'far' }} fa-circle nav-icon"></i>
                <p>Listar</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{!! route('users.create') !!}"
                class="nav-link {{ Request::is('users/create') ? 'active' : '' }}">
                <i class="{{ Request::is('users/create') ? 'fas' : 'far' }} fa-circle nav-icon"></i>
                <p>Novo</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="{{ route('logs.index') }}"
        class="nav-link {{ Request::is('logs*') ? 'active' : '' }}">
        <i class="nav-icon far fa-list-alt"></i>
        <p>Logs de atividades</p>
    </a>
</li>
@else
<li class="nav-item">
    <a href="{{ route('users.edit', ['user' => $user->id]) }}"
        class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>Alterar dados</p>
    </a>
</li>
@endif
