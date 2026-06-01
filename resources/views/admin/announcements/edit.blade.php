@extends('adminlte::page')

@section('title', 'Editar Anuncio')

@section('content_header')
    <h1>Editar Anuncio</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Anuncios</li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>
@stop

@section('plugins.Summernote', true)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="title">Título</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $announcement->title) }}" required>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Contenido</label>
                        <textarea name="content" id="content" rows="6" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $announcement->content) }}</textarea>
                        @error('content')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="course_id">Curso</label>
                                <select name="course_id" id="course_id" class="form-control">
                                    <option value="">General</option>
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $announcement->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority">Prioridad</label>
                                <select name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                    <option value="low" {{ old('priority', $announcement->priority) == 'low' ? 'selected' : '' }}>Baja</option>
                                    <option value="medium" {{ old('priority', $announcement->priority) == 'medium' ? 'selected' : '' }}>Media</option>
                                    <option value="high" {{ old('priority', $announcement->priority) == 'high' ? 'selected' : '' }}>Alta</option>
                                    <option value="urgent" {{ old('priority', $announcement->priority) == 'urgent' ? 'selected' : '' }}>Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="published_at">Fecha de publicación</label>
                                <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at', $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expires_at">Fecha de expiración</label>
                                <input type="datetime-local" name="expires_at" id="expires_at" class="form-control" value="{{ old('expires_at', $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '') }}">
                                <small class="text-muted">Dejar vacío para que no expire.</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="is_published" id="is_published" class="custom-control-input" value="1" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_published">Publicado</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script>
        $(function() {
            $('#content').summernote({
                height: 200,
                toolbar: [['style',['bold','italic','underline']],['link',['link']],['para',['ul','ol']],['clear',['clear']]]
            });
        });
    </script>
@stop
