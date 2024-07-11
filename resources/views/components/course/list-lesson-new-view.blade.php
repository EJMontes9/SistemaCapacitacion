<ul class="lesson-menu" id="lesson-list">
    @if((!isset($lesson[$sections]) || count($lesson[$sections]) == 0) && (!isset($resources[$sections]) || count($resources[$sections]) == 0))
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                <p class="ml-4">Esta sección no contiene lecciones ni recursos asignados, agrega y gestionalos en el ícono de edición</p>
            </div>
        </li>
    @else
        <?php $numLesson = 1; ?>
        @foreach ($lesson[$sections] ?? [] as $lessons)
            <li>
                <div class="flex flex-row justify-between items-center border-2 py-1 my-2 bg-gray-300 bg-opacity-50 rounded-xl">
                    <a class="ml-4"
                        href="{{ route('courses.showLesson', ['id_lesson' => $lessons->id, 'slug' => last(explode('/', request()->path()))]) }}">{{ $numLesson++ }}.
                        {{ $lessons->name }}
                    </a>
                </div>
            </li>
        @endforeach

        @foreach ($resources[$sections] ?? [] as $resource)
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
    @endif

    @if ($evaluation = $evaluation->where('module_id', $sectionsObj->id)->first())
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl mt-3 bg-cyan-200 text-cyan-900 border-cyan-300">
                <a href="{{ route('evaluations.show', $evaluation->id) }}" class="ml-4">Ver evaluación</a>
                <a href="{{ route('evaluations.view', ['evaluation' => $evaluation->id, 'user' => Auth::id()]) }}"
                    class="flex items-center text-blue-700 hover:text-blue-900 font-bold mr-4">
                    <span>Ver Resultados</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </li>
    @endif

</ul>
