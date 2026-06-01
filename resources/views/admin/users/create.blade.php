@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <div class="row ml-1">
        <h1>Crear Usuario</h1>
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
            {!! Form::open(['route' => 'admin.users.store', 'method' => 'post']) !!}

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
                <label for="password">Contraseña</label>
                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Mínimo 8 caracteres', 'required']) !!}
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Repite la contraseña', 'required']) !!}
            </div>

            <div class="form-group">
                <label for="role">Rol</label>
                {!! Form::select('role', $roles->pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'Selecciona un rol', 'required']) !!}
                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="row">
                {!! Form::submit('Crear Usuario', ['class' => 'btn btn-primary mt-2']) !!}
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
