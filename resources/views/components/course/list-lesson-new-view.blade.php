<ul class="lesson-menu" id="lesson-list">
    @if (
        (!isset($lesson[$sections]) || count($lesson[$sections]) == 0) &&
            (!isset($resources[$sections]) || count($resources[$sections]) == 0))
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                <p class="ml-4">Esta sección no contiene lecciones ni recursos asignados, agrega y gestionalos en el
                    ícono de edición</p>
            </div>
        </li>
    @else
        <?php $numLesson = 1; ?>
        @foreach ($lesson[$sections] ?? [] as $lessons)
            <li>
                <div
                    class="flex flex-row justify-between items-center border-2 py-1 my-2 bg-gray-300 bg-opacity-50 rounded-xl">
                    <a class="ml-4"
                        href="{{ route('courses.showLesson', ['id_lesson' => $lessons->id, 'slug' => last(explode('/', request()->path()))]) }}">{{ $numLesson++ }}.
                        {{ $lessons->name }}
                    </a>
                </div>
            </li>
        @endforeach

        @foreach ($resources[$sections] ?? [] as $resource)
            <li>
                <div
                    class="flex flex-row justify-between items-center border-2 py-1 my-2 bg-green-200 bg-opacity-50 rounded-xl">
                    <div class="flex items-center ml-4">
                        <a href="{{ $resource->url }}" target="_blank" class="flex items-center">
                            <i class="fa-solid fa-file-alt mr-2"></i>
                            <span>{{ $resource->name }}</span>
                        </a>
                        <span
                            class="ml-2 px-2 py-1 text-xs font-semibold rounded-full
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
    @endif

    @if ($evaluation = $evaluation->where('module_id', $sectionsObj->id)->first())
        <li>
            <div
                class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl mt-3 bg-cyan-200 text-cyan-900 border-cyan-300">
                <a href="{{ route('evaluations.show', $evaluation->id) }}" class="ml-4">Ver evaluación</a>
                @php
                    $roleId = Auth::user()->roles->pluck('id')->first();
                @endphp

                @if ($roleId == 3)
                    {{-- Alumno --}}
                    <a href="{{ route('evaluations.view', ['evaluation' => $evaluation->id, 'user' => Auth::id()]) }}"
                        class="flex items-center text-blue-700 hover:text-blue-900 font-bold mr-4">
                        <span>Ver Resultados</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                @elseif($roleId == 2 || $roleId == 1)
                    {{-- Instructor --}}
                    <div class="flex justify-end mr-5">
                        {{-- crea ruta para acceder a finished.blade donde se envien y obtengan los parametros id del curso (courseId) y el id de la seccion (sectionId) para el controlador reportePorAlumno --}}
                        <a href="{{ route('reportePorAlumno', ['courseId' => $courseid, 'sectionId' => $sectionId]) }}"
                            class="flex items-center text-blue-700 hover:text-blue-900 font-bold mr-2">
                            <i class="fas fa-square-poll-vertical"></i>
                            <span class="ml-2">Ver Resultados</span>
                        </a>
                        <p class="mx-2">|</p>
                        <a href="{{ route('evaluations.edit', ['evaluation' => $evaluation->id]) }}"
                            class="flex items-center text-green-700 hover:text-green-900 font-bold mr-2">
                            <i class="fas fa-edit"></i> {{-- Icono de editar --}}
                        </a>
                        <p class="mx-2">|</p>
                        <a href="{{ route('evaluations.unlink', ['evaluation' => $evaluation->id]) }}"
                            class="flex items-center text-red-700 hover:text-red-900 font-bold ml-1"
                            onclick="return confirm('¿Estás seguro de querer desvincular esta evaluación?');">
                            <i class="fas fa-trash"></i> {{-- Icono de eliminar --}}
                        </a>
                    </div>
                @endif
            </div>
        </li>
    @endif

</ul>
