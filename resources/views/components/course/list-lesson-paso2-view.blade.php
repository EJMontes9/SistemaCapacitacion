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
                <div class="p-4 cursor-pointer" id="acordeon-{{$sectionsObj->id}}" @click="open = !open">
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
    document.querySelectorAll('.editarLeccion').forEach(button => {
        button.addEventListener('click', (e) => {
            var lessonName = button.getAttribute('data-lessonName');
            var lessonId = button.getAttribute('data-lessonId');
            var lessonUrl = button.getAttribute('data-lessonUrl');
            var lessonIframe = button.getAttribute('data-lessonIframe');
            var sectionForm = button.getAttribute('data-form');
            var sectionId = button.getAttribute('data-secId');

            var form = document.querySelector(`#${sectionForm}`);
            form.querySelector('input[name="name"]').value = lessonName;
            form.querySelector('input[name="lesson_id"]').value = lessonId;
            form.querySelector('input[name="url"]').value = lessonUrl;
            form.querySelector('#iframe').value = lessonIframe;

            var accordion = document.querySelector(`#acordeon-${sectionId}`);
            accordion.click();    
            form.querySelector('.submit-lesson').disabled = false;
        });
    });

    // Variable para controlar el tiempo de espera
    let canSubmit = true;

    // Función para manejar el envío del formulario
    function handleSubmit(event) {
        if (!canSubmit) return;

        canSubmit = false;
        event.target.disabled = true;

        // Obtener el formulario
        const form = document.getElementById(event.target.dataset.form);

        // Crear un objeto con los valores actualizados
        const updatedData = {
            name: form.querySelector('input[name="name"]').value,
            lesson_id: form.querySelector('input[name="lesson_id"]').value,
            url: form.querySelector('input[name="url"]').value,
            iframe: form.querySelector('#iframe').value,
            section_id: form.querySelector('input[name="section_id"]').value,
            platform_id: form.querySelector('input[name="platform_id"]').value
        };

        // Crear un nuevo FormData con los datos actualizados
        const formData = new FormData();
        for (const [key, value] of Object.entries(updatedData)) {
            formData.append(key, value);
        }

        const url = new URL(location.href);
        const rootUrl = `${url.protocol}://${url.host}`;

        if (updatedData.lesson_id !== '') {
            // Disparar fetch con put para editar la lección
            fetch('/api/lessons/' + updatedData.lesson_id, {
                method: 'PUT',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                // Aquí puedes agregar código para actualizar la interfaz si es necesario
                // location.reload();
            })
            .catch(error => {
                console.error(error);
            });
        } else {
            // Disparar fetch con post para crear la lección
            fetch('/api/lessons', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                // Aquí puedes agregar código para actualizar la interfaz si es necesario
                // location.reload();
            })
            .catch(error => {
                console.error(error);
            });
        }
        event.preventDefault();
        setTimeout(() => {
            canSubmit = true;
            event.target.disabled = false;
        }, 2000);
    }

    // Agregar el evento a todos los botones de envío
    document.querySelectorAll('.submit-lesson').forEach(button => {
        button.addEventListener('click', handleSubmit);
    });
</script>