@vite(['resources/js/courses-view.js'])
<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <h1 class="text-2xl font-semibold text-gray-900 mb-4">Ahora agreguemos secciones a: {{ $course->title }}</h1>
                <img class="w-full h-48 object-cover rounded-lg mb-4" src="../../images/courses/{{ $course->image }}" alt="">
                <div class="mt-4">
                    <h3 id="agregar-secciones" class="text-lg font-semibold text-gray-600">Agrega las secciones que necesites en el siguiente formulario</h3>
                </div>
                <div id="form-secciones" class="mt-4">
                    <input type="text" id="nombre-seccion" class="bg-gray-100 text-gray-900 py-2 px-4 w-full rounded-md" placeholder="Nombre de la nueva sección">
                    <button id="guardar-seccion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full mt-2">Agregar</button>
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-0">
                    <ul id="listado-secciones" class="p-0">
                        {{-- <li class="flex items-center"> --}}
                            {{-- <i class="fas fa-play-circle text-gray-500 mr-2"></i> --}}
                            {{-- <span class="text-sm text-gray-700">A continuación se presentan las secciones del Curso</span> --}}
                            {{-- <span class="ml-auto text-xs text-gray-500">2 lectures • 50 sec</span> --}}
                        {{-- </li> --}}
                        <!-- Agrega más elementos de lista aquí -->
                    </ul>
                    {{-- <ul class="bg-gray-100 text-gray-900 py-2 px-4 w-full">             <!-- Secciones agregadas aquí -->         </ul>  --}}
                    <x-course.list-section-paso2-view :section="$section" :lesson="$lesson" :course="$course" :evaluation="$evaluation"/>
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
    const url = new URL(location.href);
    const rootUrl = `${url.protocol}://${url.host}`;
        
    const response = await fetch(`/api/sections2`, {
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