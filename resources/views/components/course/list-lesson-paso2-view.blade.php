<ul class="lesson-menu" id="lesson-list">
    @if(!isset($lesson[$sections]) || count($lesson[$sections]) == 0 )
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                <p class="ml-4">Sin lecciones asignadas</p>
            </div>
        </li>
    @else
        <?php $numLesson = 1; ?>
        @foreach ($lesson[$sections] as $lessons)
            <li>
                <div class="flex flex-row justify-between items-center border-2 ml-12 py-2 rounded-xl">
                    <a class="ml-4"
                        href="{{ route('courses.showLesson', ['id_lesson' => $lessons->id, 'slug' => last(explode('/', request()->path()))]) }}">{{ $numLesson++ }}.
                        {{ $lessons->name }}</a>
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
                <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl mt-3">
                    {{-- <a href="{{ route('lessons.create', $courseid) }}" class="ml-4">Agregar leccion</a> --}}
                    <a data-section="{{$sections}}" href="" class="ml-4">Agregar leccion</a>
                </div>
            </li>
        @endif
    @endhasanyrole
    <!-- Formulario -->
    <form id="lesson-form-{{$sectionsObj->id}}" class="space-y-6">
        <div class="form-group">
            {{-- <label for="name" class="block text-sm font-medium text-gray-700">Nombre de la lección</label> --}}
            <input type="text" id="name" name="name" placeholder="Nombre de la lección" class="block w-full p-2 pl-40 text-sm text-gray-700 bg-gray-50 rounded-md" />
        </div>

        <div class="form-group">
            <input type="hidden" value="{{$sectionsObj->id}}" id="section_id" name="section_id" class="form-control"/>
            <input type="hidden" value="1" id="platform_id" name="platform_id" class="form-control"/>
        </div>

        <div class="form-group">
            <input type="text" id="url" name="url"  placeholder="Url de la lección" class="block w-full p-2 pl-40 text-sm text-gray-700 bg-gray-50 rounded-md" />
        </div>
        <div class="form-group">
            <textarea id="iframe" name="iframe" placeholder="Iframe del video" class="block w-full p-2 pl-40 text-sm text-gray-700 bg-gray-50 rounded-md"></textarea>
        </div>
    </form>
    <button type="button" data-form="lesson-form-{{$sectionsObj->id}}" id="enviar-{{$sectionsObj->id}}" class="submit-lesson bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full mt-2 rounded">Crear lección</button>
</ul>





<script>
    // Evento para enviar el formulario por Fetch API
    document.querySelectorAll('.submit-lesson').forEach(button => {
    button.addEventListener('click', function() {
        const formData = new FormData(document.getElementById(this.dataset.form));
        const url = new URL(location.href);
        const rootUrl = `${url.protocol}://${url.host}`;
        fetch('/api/lessons', {
        method: 'POST',
        body: formData
        })
        .then(response => response.json())
        .then(data => {
        // Procesar la respuesta
        
        console.log(data);
        location.reload();
        })
        .catch(error => {
        console.error(error);
        });
    });
    });
</script>