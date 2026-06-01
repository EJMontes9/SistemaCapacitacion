@extends('adminlte::page')

@section('title', 'Calendario')

@section('content_header')
    <h1 class="font-weight-bold">Calendario</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Calendario</li>
    </ol>
@stop

@section('content')
    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Eventos del Calendario</h3>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createEventModal">
                <i class="fas fa-plus"></i> Nuevo Evento
            </button>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Eventos</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Curso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td>
                                <span class="badge" style="background-color: {{ $event->color ?? '#3B82F6' }};">&nbsp;</span>
                                {{ $event->title }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $event->type === 'class' ? 'info' : ($event->type === 'exam' ? 'danger' : ($event->type === 'deadline' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst($event->type) }}
                                </span>
                            </td>
                            <td>{{ $event->event_date->format('d/m/Y') }} @if($event->event_time) {{ substr($event->event_time, 0, 5) }} @endif</td>
                            <td>{{ $event->course->title ?? '—' }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#editEventModal{{ $event->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.calendar.destroy', $event) }}" method="POST" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este evento?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create Event Modal --}}
    <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.calendar.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo Evento</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Título <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo <span class="text-danger">*</span></label>
                                    <select class="form-control" name="type" required>
                                        <option value="class">Clase</option>
                                        <option value="exam">Examen</option>
                                        <option value="deadline">Fecha Límite</option>
                                        <option value="meeting">Reunión</option>
                                        <option value="other">Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="event_date" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Hora</label>
                                    <input type="time" class="form-control" name="event_time">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Fin</label>
                                    <input type="date" class="form-control" name="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Curso</label>
                                    <select class="form-control" name="course_id">
                                        <option value="">— Ninguno —</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ubicación</label>
                                    <input type="text" class="form-control" name="location">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Color</label>
                                    <input type="color" class="form-control" name="color" value="#3B82F6">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_all_day" name="is_all_day" value="1">
                                        <label class="form-check-label" for="is_all_day">Todo el día</label>
                                    </div>
                                </div>
                            </div>
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

    {{-- Edit Event Modals --}}
    @foreach($events as $event)
        <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.calendar.update', $event) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar: {{ $event->title }}</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Título</label>
                                        <input type="text" class="form-control" name="title" value="{{ $event->title }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo</label>
                                        <select class="form-control" name="type" required>
                                            <option value="class" {{ $event->type === 'class' ? 'selected' : '' }}>Clase</option>
                                            <option value="exam" {{ $event->type === 'exam' ? 'selected' : '' }}>Examen</option>
                                            <option value="deadline" {{ $event->type === 'deadline' ? 'selected' : '' }}>Fecha Límite</option>
                                            <option value="meeting" {{ $event->type === 'meeting' ? 'selected' : '' }}>Reunión</option>
                                            <option value="other" {{ $event->type === 'other' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea class="form-control" name="description" rows="2">{{ $event->description }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="date" class="form-control" name="event_date" value="{{ $event->event_date->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Hora</label>
                                        <input type="time" class="form-control" name="event_time" value="{{ $event->event_time ? substr($event->event_time, 0, 5) : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fecha Fin</label>
                                        <input type="date" class="form-control" name="end_date" value="{{ $event->end_date ? $event->end_date->format('Y-m-d') : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Curso</label>
                                        <select class="form-control" name="course_id">
                                            <option value="">— Ninguno —</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" {{ $event->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ubicación</label>
                                        <input type="text" class="form-control" name="location" value="{{ $event->location }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Color</label>
                                        <input type="color" class="form-control" name="color" value="{{ $event->color ?? '#3B82F6' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mt-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="edit_all_day_{{ $event->id }}" name="is_all_day" value="1" {{ $event->is_all_day ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_all_day_{{ $event->id }}">Todo el día</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.15/main.min.css" rel="stylesheet">
    <style>
        #calendar { max-height: 600px; }
        .fc-event { cursor: pointer; }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                locale: 'es',
                timeZone: 'local',
                events: '{{ route('admin.calendar.events') }}',
                editable: false,
                eventClick: function(info) {
                    alert(info.event.title + '\n' + (info.event.extendedProps.description || ''));
                }
            });
            calendar.render();
        });
    </script>

    <script>
        window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
    </script>
@stop
