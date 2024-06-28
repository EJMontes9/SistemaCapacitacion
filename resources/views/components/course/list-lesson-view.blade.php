<ul class="lesson-menu" id="lesson-list">
    @if((!isset($lesson[$sections]) || count($lesson[$sections]) == 0) && (!isset($resources[$sections]) || count($resources[$sections]) == 0))
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                <p class="ml-4">Sin lecciones ni recursos asignados</p>
            </div>
        </li>
    @else
        <?php $numLesson = 1; ?>
        @foreach ($lesson[$sections] ?? [] as $lessons)
            <li>
                <div class="flex flex-row justify-between items-center border-2 ml-12 py-2 rounded-xl">
                    <a class="ml-4"
                        href="{{ route('courses.showLesson', ['id_lesson' => $lessons->id, 'slug' => last(explode('/', request()->path()))]) }}">
                        {{ $numLesson++ }}. {{ $lessons->name }}
                    </a>
                    @hasanyrole('Instructor|Admin')
                    @if(Auth::user()->id == $usercreate->user_id)
                    <div class="px-3 flex flex-row justify-center items-center">
                        <a href="{{ route('lessons.edit',$lessons) }}" class="mr-4">
                            <i class="fa-solid fa-pen text-blue-900"></i>
                        </a>
                        <form action="{{route('lessons.destroy',$lessons)}}" method="post" class="flex flex-row items-center">
                            @csrf
                            @method('delete')
                            <button type="submit" class="mx-0 p-0 mt-4 ">
                                <i class="fa-solid fa-trash text-red-600"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                    @endhasanyrole
                </div>
            </li>
        @endforeach

        @foreach ($resources[$sections] ?? [] as $resource)
            <li>
                <div class="flex flex-row justify-between items-center border-2 ml-12 py-2 rounded-xl bg-green-100">
                    <a class="ml-4" href="{{ $resource->url }}" target="_blank">
                        <i class="fa-solid fa-file-alt mr-2"></i>{{ $resource->name }}
                    </a>
                    @hasanyrole('Instructor|Admin')
                    @if(Auth::user()->id == $usercreate->user_id)
                    <div class="px-3 flex flex-row justify-center items-center">
                        <a href="{{ route('resources.edit', $resource) }}" class="mr-4">
                            <i class="fa-solid fa-pen text-blue-900"></i>
                        </a>
                        <form action="{{ route('resources.destroy', $resource) }}" method="post" class="flex flex-row items-center">
                            @csrf
                            @method('delete')
                            <button type="submit" class="mx-0 p-0 mt-4 ">
                                <i class="fa-solid fa-trash text-red-600"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                    @endhasanyrole
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

    @hasanyrole('Instructor|Admin')
        @if (Auth::user()->id == $usercreate->user_id)
            <li>
                <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl mt-3">
                    <a href="{{ route('lessons.create', $courseid) }}" class="ml-4">Agregar lección</a>
                </div>
            </li>
            <li>
                <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl mt-3">
                    <a href="{{ route('resources.create', ['section_id' => $section_id]) }}" class="ml-4">Agregar recurso</a>
                </div>
            </li>
        @endif
    @endhasanyrole
</ul>