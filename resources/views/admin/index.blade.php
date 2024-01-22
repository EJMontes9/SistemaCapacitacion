@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <h1 class="ml-2 mt-3 font-weight-bold">StudyApp</h1>
@stop

@section('content')
    <p class="ml-2 mt-3 text-lg mb-4">
        Bienvenido al panel del Administrador {{ auth()->user()->name }}
    </p>
    <div class="row">
        <div class="col-sm-4">
            <div class="card bg-gray text-white shadow">
                <div class="card-body">
                    <i class="fas fa-book fa-2x mb-3 float-right"></i>
                    <h5 class="card-title display-4 font-weight-bold">Cursos creados</h5>
                    <p class="card-text display-4 font-weight-bold">{{ $numCursos }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <i class="fas fa-chalkboard-teacher fa-2x mb-3 float-right"></i>
                    <h5 class="card-title display-4 font-weight-bold">Usuarios instructores</h5>
                    <p class="card-text display-4 font-weight-bold">{{ $numInstructores }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <i class="fas fa-user-graduate fa-2x mb-3 float-right"></i>
                    <h5 class="card-title display-4 font-weight-bold">Usuarios alumnos</h5>
                    <p class="card-text display-4 font-weight-bold">{{ $numAlumnos }}</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Welcome!');
    </script>
@stop
