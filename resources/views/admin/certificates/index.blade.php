@extends('adminlte::page')

@section('title', 'Certificados')

@section('content_header')
    <h1>Certificados</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Certificados</li>
    </ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Plantillas de Certificados por Curso</h3>
                <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nueva Plantilla
                </a>
            </div>
            <div class="card-body">
                @if(session('info'))
                    <div class="alert alert-success">{{ session('info') }}</div>
                @endif

                @if($courses->isEmpty())
                    <div class="alert alert-info">No tienes cursos creados. Crea un curso primero.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Instructor</th>
                                    <th>Estudiantes</th>
                                    <th>Plantilla</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                @php $template = $templates->get($course->id) @endphp
                                <tr>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->teacher?->name ?? '-' }}</td>
                                    <td>{{ $course->students()->role('alumno')->count() }}</td>
                                    <td>
                                        @if($template)
                                            <span class="badge badge-success"><i class="fas fa-check"></i> Creada</span>
                                        @else
                                            <span class="badge badge-secondary"><i class="fas fa-times"></i> Sin plantilla</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if($template)
                                                <a href="{{ route('admin.certificates.show', $template) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                <a href="{{ route('admin.certificates.edit', $template) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-code"></i> Editar HTML
                                                </a>
                                                <form action="{{ route('admin.certificates.generate', $template) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Generar certificados para todos los estudiantes del curso?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-download"></i> Generar
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.certificates.create', ['course_id' => $course->id]) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-plus"></i> Crear Plantilla
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Variables disponibles en la plantilla</h3>
                        </div>
                        <div class="card-body">
                            <p>Usa estas variables en el HTML de tu plantilla:</p>
                            <table class="table table-bordered table-sm">
                                <tr><th>Variable</th><th>Descripción</th></tr>
                                <tr><td><code>@studentName</code></td><td>Nombre del estudiante</td></tr>
                                <tr><td><code>@courseName</code></td><td>Nombre del curso</td></tr>
                                <tr><td><code>@completionDate</code></td><td>Fecha de finalización (d/m/Y)</td></tr>
                                <tr><td><code>@certificateCode</code></td><td>Código único del certificado</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
