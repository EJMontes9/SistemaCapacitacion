@extends('adminlte::page')

@section('title', 'Plantilla - ' . $template->course->title)

@section('content_header')
    <h1>Plantilla de Certificado</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.certificates.index') }}">Certificados</a></li>
        <li class="breadcrumb-item active">{{ $template->course->title }}</li>
    </ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Vista Previa: {{ $template->course->title }}</h3>
                <div class="btn-group">
                    <a href="{{ route('admin.certificates.edit', $template) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-code"></i> Editar HTML
                    </a>
                    <form action="{{ route('admin.certificates.generate', $template) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Generar certificados para todos los estudiantes de este curso?')">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Generar Certificados
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body text-center">
                <p class="text-muted mb-3">Esta es una vista previa web. El PDF real puede verse diferente.</p>
                <div style="border:1px solid #ddd;display:inline-block;overflow:auto;max-width:100%;">
                    <div style="width:1123px;height:794px;overflow:auto;border:1px solid #ccc;transform:scale(0.7);transform-origin:top left;">
                        {!! \App\Models\CertificateTemplate::previewHtml($template->body_html, [
                            'studentName' => $template->course->students()->role('alumno')->first()?->name ?? 'María García López',
                            'courseName' => $template->course->title,
                            'completionDate' => now()->format('d/m/Y'),
                            'certificateCode' => 'CERT-XXXXXXXX',
                        ]) !!}
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop