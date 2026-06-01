@extends('adminlte::page')

@section('title', 'Editar Tarea')

@section('content_header')
    <h1>Editar Tarea</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.assignments.index') }}">Tareas</a></li>
        <li class="breadcrumb-item active">{{ $assignment->title }}</li>
    </ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.assignments.update', $assignment) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Curso <span class="text-danger">*</span></label>
                        <select name="course_id" class="form-control @error('course_id') is-invalid @enderror" required>
                            <option value="">Seleccione...</option>
                            @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $assignment->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Título <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $assignment->title) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $assignment->description) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha límite</label>
                                <input type="datetime-local" name="due_date" class="form-control" value="{{ old('due_date', $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Puntaje máximo <span class="text-danger">*</span></label>
                                <input type="number" name="max_score" class="form-control @error('max_score') is-invalid @enderror" value="{{ old('max_score', $assignment->max_score) }}" min="1" max="10" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mt-4">
                                    <input type="checkbox" name="allow_late" id="allow_late" class="custom-control-input" value="1" {{ old('allow_late', $assignment->allow_late) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="allow_late">Permitir entregas tardías</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop