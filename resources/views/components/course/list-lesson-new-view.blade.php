@props(['lessons', 'resources', 'section', 'course', 'evaluation'])

@php
    $userId = Auth::id();
    $isEnrolled = DB::table('course_user')
                    ->where('user_id', $userId)
                    ->where('course_id', $course->id)
                    ->exists() || $course->user_id == $userId;
@endphp

<ul class="lesson-menu" id="lesson-list">
    @if ($lessons->isEmpty() && empty($resources))
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                <p class="ml-4">Esta sección no contiene lecciones ni recursos asignados.</p>
            </div>
        </li>
    @else
        @foreach ($lessons as $lesson)
            <li class="mb-4">
                <div class="bg-gray-200 bg-opacity-100 rounded-xl px-2 pt-2 mb-0">
                    <div class="flex bg-gray-300 bg-opacity-50 hover:bg-gray-100 items-center justify-between w-full p-2 cursor-pointer border-2 rounded-xl" id="seccion-list">
                        <div class="flex flex-row justify-between items-center w-full">
                            <div class="flex flex-row flex-wrap ml-2">
                                <div class="text-sm my-auto leading-3 text-gray-700 font-bold w-full">
                                    <a class="ml-4" href="{{ route('courses.showLesson', ['id_lesson' => $lesson->id, 'slug' => $course->slug]) }}">
                                        Lección {{ $loop->iteration }}.- {{ $lesson->name }}
                                    </a>
                                    <a class="ml-2 px-3 py-1 text-xs rounded-full bg-blue-500 text-gray-200" href="{{ route('courses.showLesson', ['id_lesson' => $lesson->id, 'slug' => $course->slug]) }}">ver Lección</a>
                                    <span class="ml-2 px-3 py-1 text-xs rounded-full bg-blue-500 text-gray-200"> mostrar/ocultar recursos </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(isset($resources[$lesson->id]))
                        <ul class="ml-10">
                            @foreach ($resources[$lesson->id] as $resource)
                                <li>
                                    <div class="flex flex-row justify-between items-center border-2 py-1 my-2 bg-green-200 bg-opacity-50 rounded-xl">
                                        <div class="flex items-center ml-4">
                                            <a href="{{ $resource->url }}" target="_blank" class="flex items-center">
                                                <i class="fa-solid fa-file-alt mr-2"></i>
                                                <span>{{ $resource->name }}</span>
                                            </a>
                                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full
                                                @switch($resource->type)
                                                    @case('documento')
                                                        bg-blue-100 text-blue-600
                                                        @break
                                                    @case('imagen')
                                                        bg-green-100 text-green-600
                                                        @break
                                                    @case('url')
                                                        bg-green-400 bg-opacity-50 text-green-600
                                                        @break
                                                    @case('video')
                                                        bg-red-100 text-red-600
                                                        @break
                                                    @default
                                                        bg-gray-100 text-gray-600
                                                @endswitch
                                            ">
                                                {{ ucfirst($resource->type) }}
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </li>
        @endforeach
    @endif
    <ul>
        @forelse ($evaluation as $evl)
            <li>
                <div class="flex flex-row justify-between border-2  py-2 rounded-xl mt-3 bg-cyan-200 text-cyan-900 border-cyan-300">
                    <a href="{{ route('evaluations.show', $evl->id) }}" class="ml-4">Ver evaluación</a>
                    @php
                        $roleId = Auth::user()->roles->pluck('id')->first();
                    @endphp

                    @if ($roleId == 3)
                        {{-- Alumno --}}
                        <a href="{{ route('evaluations.view', ['evaluation' => $evl->id, 'user' => Auth::id()]) }}"
                            class="flex items-center text-blue-700 hover:text-blue-900 font-bold mr-4">
                            <span>Ver Resultados</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @elseif($roleId == 2 || $roleId == 1)
                        {{-- Instructor --}}
                        <div class="flex justify-end mr-5">
                            <a href="{{ route('reportePorAlumno', ['courseId' => $course->id, 'sectionId' => $section->id]) }}"
                                class="flex items-center text-blue-700 hover:text-blue-900 font-bold mr-2">
                                <i class="fas fa-square-poll-vertical"></i>
                                <span class="ml-2">Ver Resultados</span>
                            </a>
                            <p class="mx-2">|</p>
                            <a href="{{ route('evaluations.edit', ['evaluation' => $evl->id]) }}"
                                class="flex items-center text-green-700 hover:text-green-900 font-bold mr-2">
                                <i class="fas fa-edit"></i> {{-- Icono de editar --}}
                            </a>
                            <p class="mx-2">|</p>
                            <a href="{{ route('evaluations.unlink', ['evaluation' => $evl->id]) }}"
                                class="flex items-center text-red-700 hover:text-red-900 font-bold ml-1"
                                onclick="return confirm('¿Estás seguro de querer desvincular esta evaluación?');">
                                <i class="fas fa-trash"></i> {{-- Icono de eliminar --}}
                            </a>
                        </div>
                    @endif
                </div>
            </li>
        @empty
            <li>
                <div class="flex flex-row justify-between border-2  py-2 rounded-xl pl-5 mt-3 bg-cyan-200 text-cyan-900 border-cyan-300">
                    No hay evaluaciones disponibles para esta sección . . .
                </div>
            </li>
        @endforelse
    </ul>

</ul>

@if (!$isEnrolled)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleccionar todos los enlaces dentro de las lecciones y recursos
        const lessonLinks = document.querySelectorAll('#lesson-list a');

        // Deshabilitar el comportamiento predeterminado de los enlaces
        lessonLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
            });
        });
    });
</script>
@endif