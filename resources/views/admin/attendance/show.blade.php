@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <div class="row ml-1">
        <h1>Asistencia: {{ $session->title }}</h1>
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-success ml-auto mr-1">Regresar</a>
    </div>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">Asistencia</a></li>
        <li class="breadcrumb-item active">{{ $session->title }}</li>
    </ol>
@stop

@section('content')

    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Detalles de la Sesión</h5>
            <p><strong>Curso:</strong> {{ $session->course->title ?? 'N/A' }}</p>
            <p><strong>Fecha:</strong> {{ $session->session_date }}</p>
            @if ($session->start_time)
                <p><strong>Horario:</strong> {{ $session->start_time }} - {{ $session->end_time }}</p>
            @endif
            @if ($session->duration)
                <p><strong>Duración:</strong> {{ $session->duration }} minutos</p>
            @endif
            @if ($session->notes)
                <p><strong>Notas:</strong> {{ $session->notes }}</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => ['admin.attendance.mark', $session->id], 'method' => 'post']) !!}

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Estudiante</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                            @php
                                $attendance = $session->attendance->where('user_id', $student->id)->first();
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    {!! Form::hidden("attendance[{$index}][user_id]", $student->id) !!}
                                    {!! Form::select("attendance[{$index}][status]", ['present' => 'Presente', 'absent' => 'Ausente', 'late' => 'Tardanza'], $attendance->status ?? 'present', ['class' => 'form-control']) !!}
                                </td>
                                <td>
                                    {!! Form::text("attendance[{$index}][comment]", $attendance->comment ?? null, ['class' => 'form-control', 'placeholder' => 'Comentario opcional']) !!}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No hay estudiantes inscritos en este curso</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(count($students) > 0)
                <div class="row">
                    {!! Form::submit('Guardar Asistencia', ['class' => 'btn btn-primary mt-2']) !!}
                </div>
            @endif

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
    </script>
@stop
