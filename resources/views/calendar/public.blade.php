<x-app-layout>
    <div class="py-8 px-4 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Mi Calendario</h1>

        <div class="bg-white rounded-lg shadow p-4">
            <div id="calendar"></div>
        </div>

        <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Mis Eventos</h2>
            </div>
            <div class="p-4">
                @if($events->count() > 0)
                    <div class="space-y-3">
                        @foreach($events as $event)
                            <div class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50">
                                <div class="w-1 h-12 rounded-full" style="background-color: {{ $event->color ?? '#3B82F6' }};"></div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $event->title }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $event->event_date->format('d/m/Y') }}
                                        @if($event->event_time) · {{ substr($event->event_time, 0, 5) }} @endif
                                        @if($event->course) · {{ $event->course->title }} @endif
                                    </p>
                                    @if($event->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $event->description }}</p>
                                    @endif
                                </div>
                                <span class="text-xs badge badge-{{ $event->type === 'class' ? 'info' : ($event->type === 'exam' ? 'danger' : ($event->type === 'deadline' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst($event->type) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No tienes eventos próximos.</p>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/main.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/main.min.css" rel="stylesheet">
        <style>
            #calendar { max-height: 500px; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.15/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,listMonth'
                    },
                    locale: 'es',
                    timeZone: 'local',
                    events: [
                        @foreach($events as $event)
                            {
                                title: '{{ addslashes($event->title) }}',
                                start: '{{ $event->event_date->format('Y-m-d') }}{{ $event->event_time ? 'T' . substr($event->event_time, 0, 5) : '' }}',
                                end: '{{ $event->end_date ? $event->end_date->format('Y-m-d') : $event->event_date->format('Y-m-d') }}',
                                color: '{{ $event->color ?? '#3B82F6' }}',
                                description: '{{ addslashes($event->description ?? '') }}'
                            },
                        @endforeach
                    ],
                    eventClick: function(info) {
                        alert(info.event.title + '\n' + (info.event.extendedProps.description || 'Sin descripción'));
                    }
                });
                calendar.render();
            });
        </script>
    @endpush
</x-app-layout>
