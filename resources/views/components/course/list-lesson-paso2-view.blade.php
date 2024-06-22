<ul class="lesson-menu" id="lesson-list">
    @if(!isset($lesson[$sections]) || count($lesson[$sections]) == 0 )
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                <p class="ml-4">Esta sección no contiene lecciones asignadas, agrega una en la opción de abajo</p>
            </div>
        </li>
    @else
        <?php $numLesson = 1; ?>
        @foreach ($lesson[$sections] as $lessons)
            <li>
                <div class="flex flex-row justify-between items-center border-2 py-1 my-2 bg-gray-300 bg-opacity-50 rounded-xl">
                    <a class="ml-4"
                        href="{{ route('courses.showLesson', ['id_lesson' => $lessons->id, 'slug' => last(explode('/', request()->path()))]) }}">{{ $numLesson++ }}.
                        {{ $lessons->name }}
                    </a>
                    @hasanyrole('Instructor|Admin')
                    @if(Auth::user()->id == $usercreate->user_id)
                    <div class="px-3 flex flex-row justify-center items-center">
                        <button data-lessonName="{{ $lessons->name }}" data-lessonId="{{$lessons->id}}" data-lessonUrl="{{$lessons->url}}" data-lessonIframe="{{$lessons->iframe}}" data-secId="{{$sectionsObj->id}}"  data-form="lesson-form-{{$sectionsObj->id}}" class="btn editarLeccion mr-4">
                            <i class="fa-solid fa-pen text-blue-900"></i>
                        </button>
                        {{-- <a href="{{ route('lessons.edit',$lessons) }}" class="mr-4 ">
                            <i class="fa-solid fa-pen text-blue-900"></i>
                        </a> --}}
                        <form action="{{route('lessons.destroy',$lessons)}}" method="post" class="flex flex-row items-center  m-auto">
                            @csrf
                            @method('delete')
                            <button type="submit" class="mx-0 p-0 my-auto ">
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
            <div
                class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl mt-3 bg-cyan-200 text-cyan-900 border-cyan-300">
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
                {{-- <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl mt-3">
                    <a href="{{ route('lessons.create', $courseid) }}" class="ml-4">Agregar leccion</a> 
                </div>--}}
                {{-- formulario para agregar lecciones --}}
            </li>
        @endif
    @endhasanyrole
    <!-- Formulario -->
    <section class="pb-0 mb-1">
        <div class="max-w-3xl mx-auto px-0">
            <div class="space-y-4">
            <!-- item 1-->
            <div class="bg-blue-500 bg-opacity-50 rounded-xl shadow-sm mb-2">
                <div x-data="{ open: false }">
                <div class="p-4 cursor-pointer" id="acordeon-{{$sectionsObj->id}}" @click="open = !open; acordeonCerrado = !acordeonCerrado">
                    <h4 class="text-sm font-medium flex justify-between items-center">
                    <span>Agregar Lección a {{$sectionsObj->name}}</span>
                    <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    </h4>
                </div>
                <div x-show="open" x-transition>
                    <div class="p-4 border-t">
                        {{-- formulario para agregar lecciones --}}
                        <form id="lesson-form-{{$sectionsObj->id}}" class="space-y-6">
                            <div class="form-group">
                                {{-- <label for="name" class="block text-sm font-medium text-gray-700">Nombre de la lección</label> --}}
                                <input type="text" id="name" name="name" value="" placeholder="Nombre de la lección" class="block w-full p-2 pl-40 text-sm text-gray-700 bg-gray-50 rounded-md" />
                            </div>
                            <div class="form-group">
                                <input type="hidden" value="" id="lesson_id" name="lesson_id" class="form-control"/>
                                <input type="hidden" value="{{$sectionsObj->id}}" id="section_id" name="section_id" class="form-control"/>
                                <input type="hidden" value="1" id="platform_id" name="platform_id" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <input type="text" id="url" name="url" value="" placeholder="Url de la lección" class="block w-full p-2 pl-40 text-sm text-gray-700 bg-gray-50 rounded-md" />
                            </div>
                            <div class="form-group">
                                <textarea id="iframe" name="iframe" placeholder="Iframe del video" class="block w-full p-2 pl-40 text-sm text-gray-700 bg-gray-50 rounded-md"></textarea>
                            </div>
                        </form>
                        <button type="button" data-form="lesson-form-{{$sectionsObj->id}}" id="enviar-{{$sectionsObj->id}}" class="submit-lesson bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full mt-2 rounded">Enviar lección</button>    
                        {{-- formulario para agregar lecciones --}}
                    </div>
                </div>
                </div>
            </div>
            <!-- Resto de acordeones -->
            </div>
        </div>
    </section>
    {{-- Fin Formulario --}}
        
</ul>
<script>
    // los scripts de este componente estan en paso2-courses.blade
</script>