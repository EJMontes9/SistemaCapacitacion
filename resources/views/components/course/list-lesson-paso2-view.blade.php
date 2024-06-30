<ul class="lesson-menu" id="lesson-list">
    @if((!isset($lesson[$sections]) || count($lesson[$sections]) == 0) && (!isset($resources[$sections]) || count($resources[$sections]) == 0))
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                <p class="ml-4">Esta sección no contiene lecciones ni recursos asignados, agrega uno en la opción de abajo</p>
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
                    @hasanyrole('Instructor|Admin')
                    @if(Auth::user()->id == $usercreate->user_id)
                    <div class="px-3 flex flex-row justify-center items-center">
                        <button data-lessonName="{{ $lessons->name }}" data-lessonId="{{$lessons->id}}" data-lessonUrl="{{$lessons->url}}" data-lessonIframe="{{$lessons->iframe}}" data-secId="{{$sectionsObj->id}}"  data-form="lesson-form-{{$sectionsObj->id}}" class="btn editarLeccion mr-4">
                            <i class="fa-solid fa-pen text-blue-900"></i>
                        </button>
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
                @hasanyrole('Instructor|Admin')
                @if(Auth::user()->id == $usercreate->user_id)
                <div class="px-3 flex flex-row justify-center items-center">
                    <form action="{{ route('resources.destroy', $resource->id) }}" method="post" class="flex flex-row items-center m-auto">
                        @csrf
                        @method('delete')
                        <button type="submit" class="delete-resource mx-0 p-0 my-auto" data-resource-id="{{ $resource->id }}">
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
                <!-- Formulario -->
                <section class="pb-0 mb-1">
                    <div class="max-w-3xl mx-auto px-0">
                        <div class="space-y-4">
                            <!-- botonera de los tabs -->
                            <div class="flex justify-center mb-4">
                                <div class="border-b border-gray-200">
                                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                                        <!-- Tab 1 -->
                                        <a href="#" role="tab" class="tab-button group inline-flex items-center px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none text-indigo-600 hover:text-indigo-800" aria-current="page" data-tab="lesson">
                                            <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Gestión de Lecciones</span>
                                        </a>
                
                                        <!-- Tab 2 -->
                                        <a href="#" role="tab" class="tab-button group inline-flex items-center px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none" data-tab="resource">
                                            <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Gestión de Recursos</span>
                                        </a>
                                    </nav>
                                </div>
                            </div>
                            <!-- Contenido de los tabs -->
                            <div class="mt-6">
                                <!-- Tab 1 LECCION -->
                                <div role="tabpanel" class="tab-content p-4 bg-white rounded-lg shadow" id="lesson-tab">
                                    <div>
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
                                                        <form id="lesson-form-{{$sectionsObj->id}}" class="space-y-6">
                                                            <div class="form-group">
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
                                <!-- Tab 2 RECURSOS -->
                                <div role="tabpanel" class="tab-content p-4 bg-white rounded-lg shadow mt-4 hidden" id="resource-tab">
                                    <div>
                                        <div class="bg-blue-500 bg-opacity-50 rounded-xl shadow-sm mb-2">
                                            <div x-data="{ open: false }">
                                                <div class="p-4 cursor-pointer" id="acordeon2-{{$sectionsObj->id}}" @click="open = !open; acordeonCerrado = !acordeonCerrado">
                                                    <h4 class="text-sm font-medium flex justify-between items-center">
                                                        <span>Agregar Recursos a {{$sectionsObj->name}}</span>
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
                                                        <form id="resource-form-{{$sectionsObj->id}}" enctype="multipart/form-data">
                                                            <div class="mb-4">
                                                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Recurso</label>
                                                                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                                            </div>
                                                            <div class="mb-4">
                                                                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Tipo de Recurso</label>
                                                                <select id="type" name="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                                                    <option value="">Seleccione un tipo</option>
                                                                    <option value="documento">Documento</option>
                                                                    <option value="imagen">Imagen</option>
                                                                    <option value="url">URL</option>
                                                                    <option value="video">Video</option>
                                                                </select>
                                                            </div>
                                                            <div id="file-input" class="mb-4 hidden">
                                                                <label for="file" class="block text-gray-700 text-sm font-bold mb-2">Archivo</label>
                                                                <input type="file" id="file" name="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                            </div>
                                                            <div id="url-input" class="mb-4 hidden">
                                                                <label for="url" class="block text-gray-700 text-sm font-bold mb-2">URL</label>
                                                                <input type="url" id="url" name="url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                            </div>
                                                            <input type="hidden" name="section_id" value="{{$sectionsObj->id}}">
                                                            
                                                            
                                                        </form>
                                                        <button type="button" data-form="resource-form-{{$sectionsObj->id}}" class="submit-resource bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full mt-2 rounded">Enviar lección</button>    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </li>
        @endif
    @endhasanyrole
</ul>

<script>
    // Manejar el cambio de tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const tabId = button.getAttribute('data-tab');
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`${tabId}-tab`).classList.remove('hidden');
            
            // Actualizar estilos de los botones
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('text-indigo-600', 'border-indigo-600');
                btn.classList.add('text-gray-500', 'border-transparent');
            });
            button.classList.add('text-indigo-600', 'border-indigo-600');
            button.classList.remove('text-gray-500', 'border-transparent');
        });
    });

    // los scripts de este componente estan en paso2-courses.blade
</script>