<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nome:') !!}
    <p>{!! $showUser->name !!}</p>
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'E-mail:') !!}
    <p>{!! $showUser->email !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('created_at', 'Data criação:') !!}
    <p>{{ $showUser->created_at->format('d/m/Y H:i') }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('updated_at', 'Última atualização:') !!}
    <p>{{ $showUser->updated_at->format('d/m/Y H:i') }}</p>
</div>

