@extends('adminlte::page')

@section('title', 'StudyApp - Multimedia')

@section('content_header')
    <h1>Gestión de Archivos Multimedia</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Multimedia</li>
    </ol>
@stop

@section('content')

    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <strong>{{ session('error') }}</strong>
        </div>
    @endif

    {{-- Upload Form --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Subir Archivo</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.media.upload') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="file">Archivo</label>
                            <input type="file" name="file" id="file" class="form-control-file" required>
                            @error('file')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="title">Título</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Título del archivo">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="course_id">Curso (opcional)</label>
                            <select name="course_id" id="course_id" class="form-control">
                                <option value="">Sin curso</option>
                                @foreach ($courses as $id => $title)
                                    <option value="{{ $id }}" {{ request('course_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="type">Tipo (opcional)</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">Auto-detectar</option>
                                <option value="image">Imagen</option>
                                <option value="video">Video</option>
                                <option value="audio">Audio</option>
                                <option value="document">Documento</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea name="description" id="description" class="form-control" rows="2" placeholder="Descripción opcional del archivo"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Subir
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Quota Info --}}
    @if ($quota)
        <div class="card">
            <div class="card-body">
                <h5>Cuota de almacenamiento del curso</h5>
                @php
                    $usedPercent = $quota->max_size > 0 ? round(($quota->used_size / $quota->max_size) * 100, 1) : 0;
                    $filesPercent = $quota->max_files > 0 ? round(($quota->mediaFiles()->count() / $quota->max_files) * 100, 1) : 0;
                @endphp
                <div class="row">
                    <div class="col-md-6">
                        <p>Espacio usado: {{ number_format($quota->used_size / 1048576, 2) }} MB / {{ number_format($quota->max_size / 1048576, 2) }} MB</p>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar {{ $usedPercent > 90 ? 'bg-danger' : ($usedPercent > 70 ? 'bg-warning' : 'bg-success') }}"
                                 role="progressbar"
                                 style="width: {{ $usedPercent }}%"
                                 aria-valuenow="{{ $usedPercent }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                {{ $usedPercent }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p>Archivos: {{ $quota->mediaFiles()->count() }} / {{ $quota->max_files }}</p>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar {{ $filesPercent > 90 ? 'bg-danger' : ($filesPercent > 70 ? 'bg-warning' : 'bg-info') }}"
                                 role="progressbar"
                                 style="width: {{ $filesPercent }}%"
                                 aria-valuenow="{{ $filesPercent }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                {{ $filesPercent }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtrar Archivos</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.media.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="filter_course">Curso</label>
                            <select name="course_id" id="filter_course" class="form-control">
                                <option value="">Todos los cursos</option>
                                @foreach ($courses as $id => $title)
                                    <option value="{{ $id }}" {{ request('course_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="filter_type">Tipo</label>
                            <select name="type" id="filter_type" class="form-control">
                                <option value="">Todos los tipos</option>
                                <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Imagen</option>
                                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                                <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documento</option>
                                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="{{ route('admin.media.index') }}" class="btn btn-default ml-2">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Files Table --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Archivos Multimedia</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Vista Previa</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Tamaño</th>
                        <th>Curso</th>
                        <th>Descargas</th>
                        <th>Subido por</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($files as $file)
                        @include('admin.media._file_row', ['file' => $file])
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">No hay archivos multimedia registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($files->hasPages())
            <div class="card-footer clearfix">
                {{ $files->links() }}
            </div>
        @endif
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
