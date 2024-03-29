@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Editar Usuário</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="card">

            {!! Form::model($editUser, ['route' => ['users.update', $editUser->id], 'method' => 'patch']) !!}

            <div class="card-body">
                <div class="row">
                    @include('users.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
                <a href="{{ $user->is_admin ? route('users.index') : route('index') }}" class="btn btn-default">Cancelar</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
