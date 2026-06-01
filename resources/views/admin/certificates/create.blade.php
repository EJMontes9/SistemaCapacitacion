@extends('adminlte::page')

@section('title', 'Nueva Plantilla de Certificado')

@section('content_header')
    <h1 class="font-weight-bold">Nueva Plantilla de Certificado</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.certificates.index') }}">Certificados</a></li>
        <li class="breadcrumb-item active">Nueva Plantilla</li>
    </ol>
@stop

@section('content')
    @if (session('info'))
        <div class="alert alert-success">{{ session('info') }}</div>
    @endif

    <form action="{{ route('admin.certificates.store') }}" method="POST" id="templateForm">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Seleccionar Curso</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Curso <span class="text-danger">*</span></label>
                            <select class="form-control" name="course_id" required>
                                <option value="">Seleccione un curso...</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }} ({{ $course->students()->role('alumno')->count() }} estudiantes)
                                    </option>
                                @endforeach
                            </select>
                            @if($courses->isEmpty())
                                <small class="text-muted">Todos tus cursos ya tienen plantilla.</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Requisitos del Certificado</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_attendance">Asistencia mínima (%)</label>
                                    <input type="number" class="form-control" id="min_attendance" name="min_attendance"
                                        value="{{ old('min_attendance', 80) }}" min="0" max="100">
                                    <small class="text-muted">Porcentaje de asistencia requerido para obtener el certificado de este curso.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_grade">Calificación mínima</label>
                                    <input type="number" class="form-control" id="min_grade" name="min_grade"
                                        value="{{ old('min_grade', 70) }}" min="0" max="100">
                                    <small class="text-muted">Nota mínima promedio requerida para obtener el certificado de este curso.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Editor HTML</h3>
                        <div>
                            <button type="button" class="btn btn-info btn-sm" id="previewBtn">
                                <i class="fas fa-eye"></i> Vista Previa
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Guardar Plantilla
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>HTML del Certificado</label>
                            <small class="form-text text-muted mb-2">
                                Usa variables: <code>@studentName</code>, <code>@courseName</code>, <code>@completionDate</code>, <code>@certificateCode</code>
                            </small>
                            <div style="background:#e7f3fe;border:1px solid #b6d4fe;border-left:4px solid #0d6efd;border-radius:4px;padding:8px 12px;margin-bottom:12px;font-size:12px;color:#333;">
                                <i class="fas fa-info-circle" style="color:#0d6efd;"></i>
                                <strong>Renderizado con DomPDF (CSS 2.1).</strong>
                                Solo soporta: tablas, bordes, colores, márgenes, paddings, fuentes estándar (Times, Helvetica, Courier).
                                <strong>NO soporta:</strong> flexbox, grid, gradients, clip-path, box-shadow, transform, <code>@import</code>, Google Fonts, gap, overflow, position (solo <code>fixed</code> básico), ni border-radius en elementos de bloque.
                                Usa la plantilla por defecto como base.
                            </div>
                            <textarea class="form-control" id="body_html" name="body_html" rows="25" style="font-family: monospace; font-size: 13px; tab-size: 2;">{{ old('body_html', $defaultHtml) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Preview Modal --}}
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" style="z-index: 9999;">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vista Previa</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-0" id="previewContainer" style="min-height: 500px;">
                    <iframe id="previewFrame" style="width:100%;height:600px;border:none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.getElementById('previewBtn').addEventListener('click', function() {
            var html = document.getElementById('body_html').value;
            if (!html.trim()) { alert('Escribe algo de HTML primero.'); return; }

            var frame = document.getElementById('previewFrame');
            frame.src = '';

            fetch('{{ route('admin.certificates.preview') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ body_html: html, student_name: 'María García López', course_name: 'Curso de Ejemplo' })
            })
            .then(function(r) { return r.blob(); })
            .then(function(blob) {
                var url = URL.createObjectURL(blob);
                frame.src = url;
                $('#previewModal').modal('show');
            })
            .catch(function() { alert('Error al generar la vista previa.'); });
        });

        window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () { $(this).remove(); });
        }, 5000);
    </script>
@stop
