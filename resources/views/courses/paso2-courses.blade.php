<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <h1 class="text-2xl font-semibold text-gray-900 mb-4">Ahora agreguemos Secciones a: {{ $course->title }}</h1>
                <img class="w-full h-48 object-cover rounded-lg mb-4" src="../../images/courses/{{ $course->image }}" alt="">
                
            </div>
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Contenido del Curso</h2>

                    <ul id="listado-secciones" class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-play-circle text-gray-500 mr-2"></i>
                            <span class="text-sm text-gray-700">A continuación se presentan las secciones del Curso</span>
                            {{-- <span class="ml-auto text-xs text-gray-500">2 lectures • 50 sec</span> --}}
                        </li>
                        <!-- Agrega más elementos de lista aquí -->
                    </ul>
                    {{-- <ul class="bg-gray-100 text-gray-900 py-2 px-4 w-full">             <!-- Secciones agregadas aquí -->         </ul>  --}}
                    <?php $numSection = 1; ?>
                @foreach ($section as $sections)
                    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-gray-200 mr-4"></div>
                                <div>
                                    <div class="text-sm font-bold text-gray-700">Seccion #{{ $numSection }} :</div>
                                    <div class="text-xs text-gray-600">{{ $sections->name }}</div>
                                </div>
                            </div>
                            @hasanyrole('Instructor|Admin')
                            @if (Auth::user()->id == $course->user_id)
                                <div class="flex items-center">
                                    <a href="{{ route('sections.edit', $sections) }}" class="text-blue-600 hover:text-blue-800 mr-4">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('sections.destroy', $sections->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                            @endhasanyrole
                        </div>
                    </div>
                    <?php $numSection++; ?>
                    {{-- listado de lecciones de la sección --}}
                <x-course.list-lesson-view :lesson="$lesson" :sections="$numSection" :courseid="$sections->course_id" 
                    :section_id="$sections->id" :evaluation="$evaluation" :sectionsObj="$sections" :usercreate="$course">
                </x-course.list-lesson-view>
                {{-- fin de listado de lecciones --}}
                @endforeach
                </div>
                <div class="mt-4">
                    <button id="agregar-secciones" class="btn ">Crea las secciones en el siguiente formulario</button>
                </div>
                <div id="form-secciones" class="mt-4">
                    <input type="text" id="nombre-seccion" class="bg-gray-100 text-gray-900 py-2 px-4 w-full rounded-md" placeholder="Nombre de la sección">
                    <button id="guardar-seccion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full mt-2">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
const form = document.getElementById('form-secciones');
const nombreSeccionInput = document.getElementById('nombre-seccion');
const guardarSeccionButton = document.getElementById('guardar-seccion');
const listadoSecciones = document.getElementById('listado-secciones');

guardarSeccionButton.addEventListener('click', async () => {
    const nombreSeccion = nombreSeccionInput.value;
    const cursoId = {{ $course->id }};
    const response = await fetch(`http://sistemacapacitacion.test/api/sections2`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: nombreSeccion,
            course_id: cursoId
        })
    });
    if (response.ok) {
        location.reload();
        const nuevaSeccion = document.createElement('li');
        nuevaSeccion.textContent = nombreSeccion;
        listadoSecciones.appendChild(nuevaSeccion);
        nombreSeccionInput.value = '';
    } else {
        console.error('Error al agregar sección');
    }
});
</script>