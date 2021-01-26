<div class="table-responsive">
    <table class="table" id="users-table">
        <thead>
            <tr>
                <th style="width: 45%;">Nome</th>
                <th style="width: 40%;">E-mail</th>
                <th colspan="3" style="width: 15%;">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{!! $user->name !!}</td>
                    <td>{!! $user->email !!}</td>
                    <td class="text-center">
                        {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{!!  route('users.show', [$user->id]) !!}"
                                class='btn btn-default btn-sm'>
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{!!  route('users.edit', [$user->id]) !!}"
                                class='btn btn-default btn-sm'>
                                <i class="fa fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Deseja mesmo excluir?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
