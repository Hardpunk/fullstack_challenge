<div class="table-responsive">
    <table class="table mb-0" id="users-table">
        <thead>
            <tr>
                <th style="width: 45%;">Nome</th>
                <th style="width: 40%;">E-mail</th>
                <th colspan="3" style="width: 15%;">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $_user)
                <tr>
                    <td>{!! $_user->name !!}</td>
                    <td>{!! $_user->email !!}</td>
                    <td class="text-center">
                        {!! Form::open(['route' => ['users.destroy', $_user->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{!!  route('users.show', [$_user->id]) !!}"
                                class='btn btn-default btn-sm' title="Visualizar">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{!!  route('users.edit', [$_user->id]) !!}"
                                class='btn btn-default btn-sm' title="Editar">
                                <i class="fa fa-edit"></i>
                            </a>
                            @if($user->id === $_user->id)
                            <a href="#" class="btn btn-danger btn-sm disabled" disabled>
                                <i class="fa fa-trash"></i>
                            </a>
                            @else
                            {!! Form::button('<i class="fa fa-trash"></i>', [
                                'type' => 'submit',
                                'class' => 'btn btn-danger btn-sm',
                                'title' => 'Excluir',
                                'disabled' => false,
                                'onclick' => "return confirm('Deseja mesmo excluir?')"
                            ]) !!}
                            @endif
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
