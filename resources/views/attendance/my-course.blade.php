<x-app-layout>
    <div id="main-content" class="bg-gray-50 mx-5 my-4">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="font-bold text-2xl">Mi Asistencia - {{ $course->title }}</h1>
                <a href="{{ route('attendance.my') }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; Volver a mis cursos</a>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            @if($sessions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sessions as $index => $session)
                                @php $att = $session->attendance->first(); @endphp
                                <tr class="{{ $att && $att->status === 'absent' ? 'bg-red-50' : ($att && $att->status === 'late' ? 'bg-yellow-50' : '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($session->session_date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">{{ $session->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($att)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $att->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $att->status === 'absent' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $att->status === 'late' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                {{ ucfirst($att->status === 'present' ? 'Presente' : ($att->status === 'absent' ? 'Ausente' : 'Tardanza')) }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Sin registro</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @php
                    $present = $sessions->sum(fn($s) => $s->attendance->where('user_id', $user->id)->where('status', 'present')->count());
                    $total = $sessions->count();
                    $percentage = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                @endphp
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-lg font-semibold">Resumen: <span class="text-green-600">{{ $present }}/{{ $total }}</span> sesiones presentes ({{ $percentage }}%)</p>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No hay sesiones registradas para este curso</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>