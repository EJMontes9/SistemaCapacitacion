<x-app-layout>
    <div id="main-content" class="bg-gray-50 mx-5 my-4">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">{{ session('success') }}</strong>
            </div>
        @endif

        <div class="card">
            <h1 class="font-bold text-2xl mb-2">Libro de Calificaciones - {{ $course->title }}</h1>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <form method="GET" action="{{ route('evaluations.gradebook', $course->id) }}" class="flex items-center gap-2">
                    <label class="text-gray-600 text-sm font-medium whitespace-nowrap">Sección:</label>
                    <select name="section_id" class="form-control d-inline-block w-auto border border-gray-300 rounded px-3 py-2 text-sm" onchange="this.form.submit()">
                        <option value="">Todas las secciones</option>
                        @foreach ($course->sections as $section)
                            <option value="{{ $section->id }}" {{ $sectionFilter == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('evaluations.gradebook.export', $course->id) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Exportar CSV
                </a>
            </div>

            @if (count($gradebookData) > 0)
                <div class="overflow-x-auto shadow-lg sm:rounded-lg">
                    <table class="table-auto w-full text-sm text-left text-gray-500">
                        <thead class="font-bold text-base text-center text-black bg-gray-200">
                            <tr>
                                <th class="px-4 py-3 border">Estudiante</th>
                                @foreach ($evaluations as $evaluation)
                                    <th class="px-4 py-3 border">{{ $evaluation->title }}
                                        @if ($evaluation->section)
                                            <br><span class="text-xs font-normal">({{ $evaluation->section->name }})</span>
                                        @endif
                                    </th>
                                @endforeach
                                <th class="px-4 py-3 border">Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gradebookData as $row)
                                <tr class="{{ $loop->index % 2 == 0 ? 'bg-gray-100' : 'bg-white' }} text-center">
                                    <td class="border px-4 py-2 font-medium text-gray-900">{{ $row['student'] }}</td>
                                    @foreach ($evaluations as $evaluation)
                                        <td class="border px-4 py-2">
                                            {{ $row['evaluation_' . $evaluation->id] ?? '-' }}
                                        </td>
                                    @endforeach
                                    <td class="border px-4 py-2 font-semibold">
                                        {{ $row['average'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100 font-semibold">
                            <tr class="text-center">
                                <td class="border px-4 py-2">Promedio por evaluación</td>
                                @foreach ($evaluations as $evaluation)
                                    @php
                                        $scores = array_filter(array_map(function ($row) use ($evaluation) {
                                            $val = $row['evaluation_' . $evaluation->id] ?? null;
                                            if ($val && $val !== '-') {
                                                $parts = explode('/', $val);
                                                return floatval($parts[0]);
                                            }
                                            return null;
                                        }, $gradebookData));
                                        $avg = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : '-';
                                    @endphp
                                    <td class="border px-4 py-2">{{ $avg }}</td>
                                @endforeach
                                @php
                                    $averages = array_filter(array_map(function ($row) {
                                        return is_numeric($row['average']) ? floatval($row['average']) : null;
                                    }, $gradebookData));
                                    $overallAvg = count($averages) > 0 ? round(array_sum($averages) / count($averages), 2) : '-';
                                @endphp
                                <td class="border px-4 py-2">{{ $overallAvg }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center text-gray-500 text-xl font-semibold py-10">
                    <p>No hay estudiantes inscritos en este curso.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
