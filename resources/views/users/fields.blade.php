<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nome') !!}
    {!! Form::text('name', null, ['class' => 'form-control'. ($errors->has('name') ? ' is-invalid' : '')]) !!}
    @error('name')
        <span class="error invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'E-mail') !!}
    {!! Form::email('email', null, ['class' => 'form-control'. ($errors->has('email') ? ' is-invalid' : ''), 'autocomplete' => 'username']) !!}
    @error('email')
        <span class="error invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Senha') !!}
    {!! Form::password('password', ['class' => 'form-control'. ($errors->has('password') ? ' is-invalid' : ''), 'autocomplete' => 'new-password']) !!}
    @error('password')
        <span class="error invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<!-- Confirmation Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Confirmação de senha') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control', 'autocomplete' => 'new-password']) !!}
</div>

@if($user->is_admin)
<!-- User Role Field -->
<div class="form-group col-sm-6">
    {!! Form::label('role', 'Permissão do usuário') !!}
    {!! Form::select('role', $roles, isset($editUser->roles[0]) ? $editUser->roles[0]->name : 'client', ['class' => 'form-control']) !!}
</div>
@endif
