@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <div class="row ml-1">
        <h1>Sesiones de Clase</h1>
        <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary ml-auto mr-1">Crear Sesión</a>
    </div>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Asistencia</li>
    </ol>
@stop

@section('content')

    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Curso</th>
                            <th>Fecha</th>
                            <th>Duración</th>
                            <th colspan="3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td>{{ $session->id }}</td>
                                <td>{{ $session->title }}</td>
                                <td>{{ $session->course->title ?? 'N/A' }}</td>
                                <td>{{ $session->session_date }}</td>
                                <td>{{ $session->duration ? $session->duration . ' min' : 'N/A' }}</td>
                                <td width="10px">
                                    <a class="btn btn-info btn-sm" href="{{ route('admin.attendance.show', $session) }}">Asistencia</a>
                                </td>
                                <td width="10px">
                                    <a class="btn btn-warning btn-sm" href="{{ route('admin.attendance.edit', $session) }}">Editar</a>
                                </td>
                                <td width="10px">
                                    <form action="{{ route('admin.attendance.destroy', $session) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta sesión?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No hay sesiones registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $sessions->links() }}
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
