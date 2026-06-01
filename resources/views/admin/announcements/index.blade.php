@extends('adminlte::page')

@section('title', 'Anuncios')

@section('content_header')
    <h1>Anuncios</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Anuncios</li>
    </ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Anuncios</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Anuncio
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
                    <div class="form-group mr-2">
                        <select name="priority" class="form-control form-control-sm">
                            <option value="">Todas las prioridades</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Media</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-info mr-2">Filtrar</button>
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-sm btn-secondary">Limpiar</a>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Curso</th>
                                <th>Prioridad</th>
                                <th>Publicado</th>
                                <th>Expira</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($announcements as $announcement)
                            <tr>
                                <td>{{ $announcement->title }}</td>
                                <td>{{ $announcement->course ? $announcement->course->title : 'General' }}</td>
                                <td>
                                    @php
                                        $priorityColors = ['low' => 'badge-secondary', 'medium' => 'badge-info', 'high' => 'badge-warning', 'urgent' => 'badge-danger'];
                                    @endphp
                                    <span class="badge {{ $priorityColors[$announcement->priority] ?? 'badge-secondary' }}">
                                        {{ ucfirst($announcement->priority) }}
                                    </span>
                                </td>
                                    <td>
                                        @if($announcement->is_published)
                                            <span class="badge badge-success">Sí</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($announcement->expires_at)
                                            @if($announcement->expires_at->isFuture())
                                                <span class="badge badge-info">{{ $announcement->expires_at->format('d/m/Y') }}</span>
                                            @else
                                                <span class="badge badge-secondary">Expirado</span>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $announcement->published_at ? $announcement->published_at->format('d/m/Y H:i') : $announcement->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar anuncio?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay anuncios registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</div>
@stop
