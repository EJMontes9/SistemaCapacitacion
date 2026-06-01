<x-app-layout>
    <div id="main-content" class="bg-gray-50 mx-5 my-4">
        <div class="card mb-4">
            <h1 class="font-bold text-2xl mb-2">Mi Asistencia</h1>
            <p class="text-gray-600">Selecciona un curso para ver tu registro de asistencia</p>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            @if($courses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($courses as $course)
                        @php
                            $totalSessions = $course->classSessions->count();
                            $present = $course->classSessions->sum(fn($s) => $s->attendance->where('user_id', $user->id)->where('status', 'present')->count());
                            $percentage = $totalSessions > 0 ? round(($present / $totalSessions) * 100, 1) : 0;
                        @endphp
                        <a href="{{ route('attendance.my.course', $course->id) }}"
                           class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-lg hover:border-blue-300 transition-all duration-200">
                            <h5 class="text-lg font-bold text-gray-900 mb-2">{{ $course->title }}</h5>
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Sesiones: {{ $totalSessions }}</span>
                                <span>Asistencia: {{ $percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No estás inscrito en ningún curso</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>