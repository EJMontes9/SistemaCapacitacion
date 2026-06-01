@extends('adminlte::page')

@section('title', 'Entregas - ' . $assignment->title)

@section('content_header')
    <h1>Entregas: {{ $assignment->title }}</h1>
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
            <div class="card-header">
                <h3 class="card-title">{{ $assignment->course->title }} — Puntaje máximo: {{ $assignment->max_score }}</h3>
            </div>
            <div class="card-body">
                @if(session('info'))
                    <div class="alert alert-success">{{ session('info') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="mb-3">
                    <span class="badge badge-info">Máximo: {{ $assignment->max_score }}/10</span>
                    @if($assignment->due_date)
                        <span class="badge badge-secondary">Límite: {{ $assignment->due_date->format('d/m/Y H:i') }}</span>
                    @endif
                    @if($assignment->allow_late)
                        <span class="badge badge-warning">Permite entregas tardías</span>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Texto</th>
                                <th>Archivo</th>
                                <th>Fecha entrega</th>
                                <th>Estado</th>
                                <th>Calificación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignment->submissions as $sub)
                            @php
                                $isLate = $assignment->due_date && $sub->submitted_at && $sub->submitted_at->greaterThan($assignment->due_date);
                            @endphp
                            <tr>
                                <td>{{ $sub->user->name }}</td>
                                <td>{{ Str::limit($sub->text_content, 60) ?: '—' }}</td>
                                <td>
                                    @if($sub->file_path)
                                        <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-file"></i> {{ $sub->file_name }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $sub->submitted_at ? $sub->submitted_at->format('d/m/Y H:i') : '—' }}</td>
                                <td>
                                    @if($isLate)
                                        <span class="badge badge-warning">Atrasada</span>
                                    @else
                                        <span class="badge badge-success">En tiempo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sub->score !== null)
                                        <span class="badge badge-{{ $sub->score >= ($assignment->max_score / 2) ? 'success' : 'danger' }}">
                                            {{ $sub->score }}/{{ $assignment->max_score }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Sin calificar</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#gradeModal{{ $sub->id }}">
                                        <i class="fas fa-star"></i> Calificar
                                    </button>
                                </td>
                            </tr>

                            {{-- Grade Modal --}}
                            <div class="modal fade" id="gradeModal{{ $sub->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.assignments.grade', $assignment) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="submission_id" value="{{ $sub->id }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Calificar: {{ $sub->user->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                @if($sub->text_content)
                                                <div class="mb-3">
                                                    <label>Texto entregado:</label>
                                                    <div class="p-2 bg-gray-50 rounded">{{ $sub->text_content }}</div>
                                                </div>
                                                @endif
                                                <div class="form-group">
                                                    <label>Puntaje (0 - {{ $assignment->max_score }})</label>
                                                    <input type="number" name="score" class="form-control" value="{{ $sub->score }}" min="0" max="{{ $assignment->max_score }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Retroalimentación</label>
                                                    <textarea name="feedback" class="form-control" rows="3">{{ $sub->feedback }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr><td colspan="7" class="text-center">No hay entregas aún.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop