<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Editar curso de {{ $course->title }}</h1>
        <x-validation-errors />
        <form action="{{route('courses.update',$course->id )}}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <x-course.form-courses :categories="$categories" :levels="$levels" :course="$course" :modalidades="$modalidades" :periodos="$periodos" :sedes="$sedes" :niveles="$niveles" />
            <x-button class="mt-4" type="submit">Save</x-button>
        </form>

        {{-- Media Files Section --}}
        <div class="mt-8 p-4 bg-white rounded shadow">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Archivos Multimedia del Curso</h2>

            @php
                $quota = $course->quota ?? \App\Models\CourseQuota::firstOrCreate(
                    ['course_id' => $course->id],
                    ['max_size' => 524288000, 'max_files' => 100]
                );
                $mediaFiles = $course->mediaFiles()->orderBy('created_at', 'desc')->get();
                $usedPercent = $quota->max_size > 0 ? round(($quota->used_size / $quota->max_size) * 100, 1) : 0;
            @endphp

            {{-- Quota Bar --}}
            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    Espacio usado: {{ number_format($quota->used_size / 1048576, 2) }} MB /
                    {{ number_format($quota->max_size / 1048576, 2) }} MB
                    ({{ $quota->mediaFiles()->count() }} / {{ $quota->max_files }} archivos)
                </p>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full {{ $usedPercent > 90 ? 'bg-red-500' : ($usedPercent > 70 ? 'bg-yellow-500' : 'bg-green-500') }}"
                         style="width: {{ $usedPercent }}%">
                    </div>
                </div>
            </div>

            {{-- Upload Form --}}
            <form action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4 p-3 bg-gray-50 rounded">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <div class="flex flex-wrap items-start gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Archivo</label>
                        <input type="file" name="file" required class="border p-2 rounded w-full">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                        <input type="text" name="title" class="border p-2 rounded w-full" placeholder="Título del archivo">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select name="type" class="border p-2 rounded">
                            <option value="">Auto</option>
                            <option value="image">Imagen</option>
                            <option value="video">Video</option>
                            <option value="audio">Audio</option>
                            <option value="document">Documento</option>
                            <option value="other">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="mt-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="description" class="border p-2 rounded w-full" rows="2" placeholder="Descripción opcional"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-2">Subir</button>
            </form>

            {{-- Files List --}}
            @if ($mediaFiles->count())
                <table class="min-w-full bg-white border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border text-left">Archivo</th>
                            <th class="p-2 border text-left">Tipo</th>
                            <th class="p-2 border text-left">Tamaño</th>
                            <th class="p-2 border text-left">Descargas</th>
                            <th class="p-2 border text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mediaFiles as $file)
                            <tr class="border-t">
                                <td class="p-2 border">{{ $file->original_name }}</td>
                                <td class="p-2 border">{{ $file->type }}</td>
                                <td class="p-2 border">{{ $file->size_formatted }}</td>
                                <td class="p-2 border">{{ $file->downloads_count }}</td>
                                <td class="p-2 border flex gap-2">
                                    <a href="{{ route('admin.media.download', $file) }}" class="text-blue-600 hover:underline">Descargar</a>
                                    <a href="{{ route('admin.media.stream', $file) }}" target="_blank" class="text-green-600 hover:underline">Ver</a>
                                    <form action="{{ route('admin.media.destroy', $file) }}" method="POST" onsubmit="return confirm('¿Eliminar este archivo?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">No hay archivos multimedia en este curso.</p>
            @endif
        </div>

        <div class="mt-8 p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Tareas del Curso</h2>
                    <p class="text-sm text-gray-500">Administra las tareas desde esta pantalla del curso.</p>
                </div>
                <a href="{{ route('admin.assignments.create', ['course_id' => $course->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Nueva tarea
                </a>
            </div>

            @if($assignments->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 border text-left">Título</th>
                                <th class="p-2 border text-left">Límite</th>
                                <th class="p-2 border text-left">Máx.</th>
                                <th class="p-2 border text-left">Estado</th>
                                <th class="p-2 border text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                <tr>
                                    <td class="p-2 border">{{ $assignment->title }}</td>
                                    <td class="p-2 border">{{ $assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'Sin fecha' }}</td>
                                    <td class="p-2 border">{{ $assignment->max_score }}/10</td>
                                    <td class="p-2 border">
                                        @if($assignment->allow_late)
                                            <span class="text-orange-600 font-semibold">Permite tardías</span>
                                        @else
                                            <span class="text-gray-600">Cierra al vencimiento</span>
                                        @endif
                                    </td>
                                    <td class="p-2 border">
                                        <a href="{{ route('admin.assignments.submissions', $assignment) }}" class="text-blue-600 hover:underline">Ver entregas</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Este curso aún no tiene tareas.</p>
            @endif
        </div>
    </div>
</x-app-layout>
