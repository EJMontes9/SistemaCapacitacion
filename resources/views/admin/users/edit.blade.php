@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <div class="row ml-1">
        <h1>Asignar un Rol</h1>
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
            <h1 class="h5">Nombre:</h1>
            <p class="form-control">{{ $user->name }}</p>

            <h1 class="h5">Listado de Roles</h1>
            {!! Form::model($user, ['route' => ['admin.users.update', $user], 'method' => 'put']) !!}

            @foreach ($roles as $role)
                <div>
                    <label>
                        {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'mr-1']) !!}
                        {{ $role->name }}
                    </label>
                </div>
            @endforeach

            <div class=row>
                {!! Form::submit('Guardar', ['class' => 'btn btn-primary mt-2']) !!}

                <a href="{{ route('admin.users.index') }}" class="btn btn-danger mt-3 ml-auto">Cancelar</a>
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
