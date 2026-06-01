@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <div class="row ml-1">
        <h1>Editar Usuario: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-success ml-auto mr-1">Regresar</a>
    </div>
@stop

@section('content')

    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            {!! Form::model($user, ['route' => ['admin.users.update', $user], 'method' => 'put']) !!}

            <div class="form-group">
                <label for="name">Nombre</label>
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre completo', 'required']) !!}
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'correo@ejemplo.com', 'required']) !!}
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña <small>(Dejar vacío para mantener la actual)</small></label>
                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Mínimo 8 caracteres']) !!}
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Repite la contraseña']) !!}
            </div>

            <h1 class="h5 mt-4">Listado de Roles</h1>

            @foreach ($roles as $role)
                <div>
                    <label>
                        {!! Form::checkbox('roles[]', $role->id, $user->hasRole($role->name), ['class' => 'mr-1']) !!}
                        {{ $role->name }}
                    </label>
                </div>
            @endforeach

            <div class="row">
                {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary mt-2']) !!}
                <a href="{{ route('admin.users.index') }}" class="btn btn-danger mt-2 ml-auto">Cancelar</a>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 5000);
    </script>
@stop
