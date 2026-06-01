@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <div class="row ml-1">
        <h1>{{ isset($session) ? 'Editar Sesión' : 'Crear Sesión' }}</h1>
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-success ml-auto mr-1">Regresar</a>
    </div>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Asistencia</a></li>
        <li class="breadcrumb-item active">{{ isset($session) ? 'Editar' : 'Crear' }}</li>
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
            {!! Form::open(['route' => isset($session) ? ['admin.attendance.update', $session] : 'admin.attendance.store', 'method' => isset($session) ? 'put' : 'post']) !!}

            <div class="form-group">
                <label for="course_id">Curso</label>
                {!! Form::select('course_id', $courses->pluck('title', 'id'), isset($session) ? $session->course_id : null, ['class' => 'form-control', 'placeholder' => 'Selecciona un curso', 'required']) !!}
                @error('course_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="section_id">Sección <small>(Opcional)</small></label>
                {!! Form::select('section_id', $sections->pluck('name', 'id'), isset($session) ? $session->section_id : null, ['class' => 'form-control', 'placeholder' => 'Selecciona una sección']) !!}
                @error('section_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="lesson_id">Lección <small>(Opcional)</small></label>
                {!! Form::select('lesson_id', $lessons->pluck('name', 'id'), isset($session) ? $session->lesson_id : null, ['class' => 'form-control', 'placeholder' => 'Selecciona una lección']) !!}
                @error('lesson_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="title">Título</label>
                {!! Form::text('title', isset($session) ? $session->title : null, ['class' => 'form-control', 'placeholder' => 'Título de la sesión', 'required']) !!}
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="session_date">Fecha</label>
                {!! Form::date('session_date', isset($session) ? $session->session_date : null, ['class' => 'form-control', 'required']) !!}
                @error('session_date') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_time">Hora Inicio</label>
                        {!! Form::time('start_time', isset($session) ? $session->start_time : null, ['class' => 'form-control', 'id' => 'start_time']) !!}
                        @error('start_time') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_time">Hora Fin</label>
                        {!! Form::time('end_time', isset($session) ? $session->end_time : null, ['class' => 'form-control', 'id' => 'end_time']) !!}
                        @error('end_time') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="duration">Duración (minutos)</label>
                        {!! Form::number('duration', isset($session) ? $session->duration : null, ['class' => 'form-control', 'id' => 'duration', 'readonly' => true, 'placeholder' => 'Se calcula automáticamente']) !!}
                        @error('duration') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="notes">Notas</label>
                {!! Form::textarea('notes', isset($session) ? $session->notes : null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Notas adicionales...']) !!}
                @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="row">
                {!! Form::submit(isset($session) ? 'Actualizar Sesión' : 'Crear Sesión', ['class' => 'btn btn-primary mt-2']) !!}
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-danger mt-2 ml-auto">Cancelar</a>
            </div>

            {!! Form::close() !!}
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

        function calcularDuracion() {
            var start = $('#start_time').val();
            var end = $('#end_time').val();
            if (start && end) {
                var partsStart = start.split(':');
                var partsEnd = end.split(':');
                var startMin = parseInt(partsStart[0]) * 60 + parseInt(partsStart[1]);
                var endMin = parseInt(partsEnd[0]) * 60 + parseInt(partsEnd[1]);
                var diff = endMin - startMin;
                if (diff > 0) {
                    $('#duration').val(diff);
                } else {
                    $('#duration').val('');
                }
            } else {
                $('#duration').val('');
            }
        }

        $('#start_time, #end_time').on('change', calcularDuracion);
    </script>
@stop
