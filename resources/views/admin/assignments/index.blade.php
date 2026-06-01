@extends('adminlte::page')

@section('title', 'Tareas')

@section('content_header')
    <h1>Tareas</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Tareas</li>
    </ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Tareas</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nueva Tarea
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('info'))
                    <div class="alert alert-success">{{ session('info') }}</div>
                @endif

                <form method="GET" class="form-inline mb-3">
                    <div class="form-group mr-2">
                        <select name="course_id" class="form-control form-control-sm">
                            <option value="">Todos los cursos</option>
                            @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-info mr-2">Filtrar</button>
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-sm btn-secondary">Limpiar</a>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Curso</th>
                                <th>Fecha límite</th>
                                <th>Puntaje máx.</th>
                                <th>Entregas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                            <tr>
                                <td>{{ $assignment->title }}</td>
                                <td>{{ $assignment->course->title }}</td>
                                <td>{{ $assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'Sin fecha' }}</td>
                                <td>{{ $assignment->max_score }}</td>
                                <td>{{ $assignment->submissions()->count() }}</td>
                                <td>
                                    <a href="{{ route('admin.assignments.submissions', $assignment) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-list"></i> Entregas
                                    </a>
                                    <a href="{{ route('admin.assignments.edit', $assignment) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar tarea?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center">No hay tareas registradas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $assignments->links() }}
            </div>
        </div>
    </div>
</div>
@stop