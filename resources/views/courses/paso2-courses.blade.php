<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Ahora agreguemos Secciones a: {{ $course->title }}</h1>
        <ul id="listado-secciones" class="bg-gray-100 text-gray-900 py-2 px-4 w-full">
            <!-- Secciones agregadas aquí -->
        </ul>
        <div id="form-secciones">
            <button id="agregar-secciones" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Agregar secciones</button>
            <input type="text" id="nombre-seccion" class="bg-gray-100 text-gray-900 py-2 px-4 w-full">
            <button id="guardar-seccion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
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
        const nuevaSeccion = document.createElement('li');
        nuevaSeccion.textContent = nombreSeccion;
        listadoSecciones.appendChild(nuevaSeccion);
        nombreSeccionInput.value = '';
    } else {
        console.error('Error al agregar sección');
    }
});
</script>