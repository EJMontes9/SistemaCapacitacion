<x-app-layout>
    <div id="main-content" class="bg-gray-50 mx-5 my-4">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="font-bold text-2xl">Dashboard de Asistencia - {{ $course->title }}</h1>
                <a href="{{ route('admin.attendance.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">&larr; Volver a sesiones</a>
            </div>
        </div>

        @if (session('info'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">{{ session('info') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Total Sesiones</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $course->classSessions->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Total Estudiantes</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $students->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-gray-500 text-sm font-medium">Asistencia Promedio</h3>
                @php
                    $avg = $stats->avg('percentage');
                @endphp
                <p class="text-3xl font-bold {{ $avg >= 80 ? 'text-green-600' : ($avg >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ number_format($avg, 1) }}%
                </p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estudiante</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Presente</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Ausente</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tardanza</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">% Asistencia</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($stats as $stat)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $stat['name'] }}</td>
                                <td class="px-6 py-4 text-center text-green-600 font-semibold">{{ $stat['present'] }}</td>
                                <td class="px-6 py-4 text-center text-red-600 font-semibold">{{ $stat['absent'] }}</td>
                                <td class="px-6 py-4 text-center text-yellow-600 font-semibold">{{ $stat['late'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $stat['percentage'] >= 80 ? 'bg-green-100 text-green-800' : ($stat['percentage'] >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $stat['percentage'] }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay estudiantes inscritos</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>