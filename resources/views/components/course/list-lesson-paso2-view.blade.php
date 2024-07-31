<ul class="lesson-menu" id="lesson-list">
    @if((!isset($lesson[$sections]) || count($lesson[$sections]) == 0))
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                <p class="ml-4">Esta sección no contiene lecciones asignadas, agrega una en la opción de abajo</p>
            </div>
        </li>
    @else
            <?php $numLesson = 1; ?>
        @foreach ($lesson[$sections] ?? [] as $lessons)
            <li>
                <div class="flex flex-row justify-between items-center border-2 py-1 my-2 bg-gray-300 bg-opacity-50 rounded-xl">
                    <a class="ml-4"
                       href="{{ route('courses.showLesson', ['id_lesson' => $lessons->id, 'slug' => last(explode('/', request()->path()))]) }}">{{ $numLesson++ }}
                        .
                        {{ $lessons->name }}
                    </a>
                    @hasanyrole('Instructor|Admin')
                    @if(Auth::user()->id == $usercreate->user_id)
                        <div class="px-3 flex flex-row justify-center items-center">
                            <button data-lessonName="{{ $lessons->name }}" data-lessonId="{{$lessons->id}}"
                                    data-lessonUrl="{{$lessons->url}}" data-lessonIframe="{{$lessons->iframe}}"
                                    data-secId="{{$sectionsObj->id}}" data-form="lesson-form-{{$sectionsObj->id}}"
                                    class="btn editarLeccion mr-4">
                                <i class="fa-solid fa-pen text-blue-900"></i>
                            </button>
                            <form action="{{route('lessons.destroy',$lessons)}}" method="post"
                                  class="flex flex-row items-center  m-auto">
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

                <!-- Recursos de la lección -->
                <ul class="ml-8 resources-list" data-lesson-id="{{ $lessons->id }}">
                    <!-- Los recursos se cargarán aquí dinámicamente -->
                </ul>

                <!-- Formulario para agregar recursos a la lección -->
                @hasanyrole('Instructor|Admin')
                @if(Auth::user()->id == $usercreate->user_id)
                    <div class="ml-8 mt-2">
                        <div x-data="{ open: false }">
                            <button @click="open = !open"
                                    class=" w-full bg-blue-500 bg-opacity-50 text-blue-700 text-sm px-4 py-2 rounded-md">
                                Agregar Recurso
                            </button>
                            <div x-show="open" class="mt-2">
                                <form id="resource-form-{{$lessons->id}}" class="space-y-4"
                                      enctype="multipart/form-data">
                                    <input type="text" name="name" placeholder="Nombre del recurso"
                                           class="w-full p-2 border rounded">
                                    <select name="type" class="w-full p-2 border rounded">
                                        <option value="">Seleccione un tipo</option>
                                        <option value="documento">Documento</option>
                                        <option value="imagen">Imagen</option>
                                        <option value="url">URL</option>
                                        <option value="video">Video</option>
                                    </select>
                                    <input type="file" name="file" class="file-input w-full p-2 border rounded hidden">
                                    <input type="url" name="url" placeholder="URL del recurso"
                                           class="url-input w-full p-2 border rounded hidden">
                                    <input type="hidden" name="lesson_id" value="{{$lessons->id}}">
                                    <button type="button" data-form="resource-form-{{$lessons->id}}"
                                            class="submit-resource bg-green-500 text-white px-4 py-2 rounded-md">
                                        Guardar Recurso
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                @endhasanyrole
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
            <!-- Formulario para agregar lecciones -->
            <div class="bg-blue-500 bg-opacity-50 rounded-xl shadow-sm mb-2">
                <div x-data="{ open: false }">
                    <div class="p-4 cursor-pointer" id="acordeon-{{$sectionsObj->id}}" @click="open = !open">
                        <h4 class="text-sm font-medium flex justify-between items-center">
                            <span>Agregar Lección a {{$sectionsObj->name}}</span>
                            <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                            <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 15l7-7 7 7"/>
                            </svg>
                        </h4>
                    </div>
                    <div x-show="open" x-transition>
                        <div class="p-4 border-t">
                            <form id="lesson-form-{{$sectionsObj->id}}" class="space-y-6">
                                <div class="form-group">
                                    <input type="text" id="name" name="name" value="" placeholder="Nombre de la lección"
                                           class="block w-full p-2 text-sm text-gray-700 bg-gray-50 rounded-md"/>
                                </div>
                                <div class="form-group">
                                    <textarea id="description" name="description" placeholder="Descripción del video"
                                              class="block w-full p-2 text-sm text-gray-700 bg-gray-50 rounded-md"></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" value="" id="lesson_id" name="lesson_id" class="form-control"/>
                                    <input type="hidden" value="{{$sectionsObj->id}}" id="section_id" name="section_id"
                                           class="form-control"/>
                                    <input type="hidden" value="1" id="platform_id" name="platform_id"
                                           class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="url" name="url" value="" placeholder="Url de la lección"
                                           class="block w-full p-2 text-sm text-gray-700 bg-gray-50 rounded-md"/>
                                </div>
                                <div class="form-group">
                                    <textarea id="iframe" name="iframe" placeholder="Iframe del video"
                                              class="block w-full p-2 text-sm text-gray-700 bg-gray-50 rounded-md"></textarea>
                                </div>
                            </form>
                            <button type="button" data-form="lesson-form-{{$sectionsObj->id}}"
                                    id="enviar-{{$sectionsObj->id}}"
                                    class="submit-lesson bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full mt-2 rounded">
                                Enviar lección
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    @endif
    @endhasanyrole
</ul>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('select[name="type"]').forEach(select => {
            select.addEventListener('change', function () {
                const form = this.closest('form');
                const fileInput = form.querySelector('input[name="file"]');
                const urlInput = form.querySelector('input[name="url"]');
                const selectedType = this.value;

                // Hide all inputs initially
                fileInput.classList.add('hidden');
                urlInput.classList.add('hidden');

                // Show the appropriate input based on the selected type
                if (selectedType === 'documento' || selectedType === 'imagen') {
                    fileInput.classList.remove('hidden');
                    fileInput.accept = selectedType === 'documento' ? '.pdf,.doc,.docx' : 'image/*';
                } else if (selectedType === 'url') {
                    urlInput.classList.remove('hidden');
                }
            });
        });

        document.querySelectorAll('input[name="file"]').forEach(fileInput => {
            fileInput.addEventListener('change', function () {
                const selectedType = this.closest('form').querySelector('select[name="type"]').value;
                const file = this.files[0];
                const validDocumentTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

                if (selectedType === 'documento' && !validDocumentTypes.includes(file.type)) {
                    alert('Por favor, sube un documento válido (PDF, DOC, DOCX).');
                    this.value = '';
                } else if (selectedType === 'imagen' && !validImageTypes.includes(file.type)) {
                    alert('Por favor, sube una imagen válida (JPEG, PNG, GIF).');
                    this.value = '';
                }
            });
        });
    });
</script>

