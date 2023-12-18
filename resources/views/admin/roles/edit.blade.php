@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <div class="row ml-1">
        <h1>Editar Rol</h1>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-success ml-auto mr-1">Regresar</a>
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
            {!! Form::model($role, ['route' => ['admin.roles.update', $role], 'method' => 'put']) !!}

            @include('admin.roles.partials.form')

            <div class="row">
                {!! Form::submit('Actualizar Rol', ['class' => 'btn btn-primary mt-3']) !!}

                <a href="{{ route('admin.roles.index') }}" class="btn btn-danger mt-3 ml-auto">Cancelar</a>
            </div>

            {!! Form::close() !!}
        </div>
</div>@stop

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
