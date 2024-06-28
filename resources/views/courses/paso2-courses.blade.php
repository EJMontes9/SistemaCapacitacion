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
                    <button id="guardar-seccion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full mt-2">Agregar Sección</button>
                    <a href="{{route('courses.show',$course->slug)}}" id="finalizar-curso" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 flex justify-center rounded w-100 mt-4">Finalizar</a>
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
                    <x-course.list-section-paso2-view :section="$section" :resources="$resources" :lesson="$lesson" :course="$course" :evaluation="$evaluation"/>
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


{{-- script de lecciones --}}
<script>
    //Aqui borramos las keys de edicion antes de actualizar la vista
    Object.keys(localStorage).forEach(key => { if (key.includes("lessonData_lesson-form")) { delete localStorage[key]; }});

    let acordeonCerrado = false; // acordeon de formulario de lecciones en estado cerrado, cambia con el boton edit o al desplegar el acordeon
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

            // Crear el objeto inicial en localStorage
            updateLocalStorage(sectionForm);
            var accordion = document.querySelector(`#acordeon-${sectionId}`);

            if (!acordeonCerrado){ //cada vez que se abre o cierra un acordeon cambiamos el estado para que se despliegue correctamente en cada edit
                accordion.click();
                acordeonCerrado = true;
            }
                
            // form.querySelector('.submit-lesson').disabled = false;

            // Agregar eventos a los campos del formulario
            addFormFieldEvents(form);
        });
    });

    function addFormFieldEvents(form) {
        const formId = form.id;
        const fields = form.querySelectorAll('input, textarea');
        fields.forEach(field => {
            // ['focus', 'input', 'change'].forEach(eventType => {
            ['input','change'].forEach(eventType => {
                field.addEventListener(eventType, () => updateLocalStorage(formId));
            });
        });
    }

    function updateLocalStorage(formId) {
        const form = document.getElementById(formId);
        const data = {
            name: form.querySelector('input[name="name"]').value,
            lesson_id: form.querySelector('input[name="lesson_id"]').value,
            url: form.querySelector('input[name="url"]').value,
            iframe: form.querySelector('#iframe').value,
            section_id: form.querySelector('input[name="section_id"]').value,
            platform_id: form.querySelector('input[name="platform_id"]').value
        };
        localStorage.setItem(`lessonData_${formId}`, JSON.stringify(data));
        console.log(`Datos actualizados en localStorage para ${formId}:`, data);
    }

    // Variable para controlar el tiempo de espera
    let canSubmit = true;

    // Función para manejar el envío del formulario
    function handleSubmit(event) {
        // let canSubmit = true;
        if (!canSubmit) return;

        canSubmit = false;
        event.target.disabled = true;

        const formId = event.target.dataset.form;
        const storedData = JSON.parse(localStorage.getItem(`lessonData_${formId}`));
        
        if (!storedData) {
            // si no se encuentra id en el formulario de la lección creara una 
            const newformData = new FormData(document.getElementById(formId));;
            const formDataJson = Object.fromEntries(newformData.entries());
            // Disparar fetch con post para crear la lección
            fetch('/api/lessons', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formDataJson)
            })
            .then(response => response.json())
            .then(data => {
                // console.log(data);
                // Aquí puedes agregar código para actualizar la interfaz si es necesario
                
                location.reload();
            })
            .catch(error => {
                console.error(error);
            });
            console.error('No se encontraron datos en localStorage');
            return;
        }

        const formData = new FormData();
        for (const [key, value] of Object.entries(storedData)) {
            formData.append(key, value);
        }

        const url = new URL(location.href);
        const rootUrl = `${url.protocol}://${url.host}`;

        if (storedData.lesson_id !== '') {
            const storedData2 = JSON.parse(localStorage.getItem(`lessonData_${formId}`)); // llamamos los datos otra vez por si acaso no esten actualizandose
            const formData2 = new FormData(); //creamos nuevo formdata por los valores actualizados 
        for (const [key, value] of Object.entries(storedData)) {
            formData2.append(key, value);
        }
            console.log(JSON.stringify(storedData2)); //para verificar si se actualizan los datos
            // Disparar fetch con put para editar la lección
            fetch('/api/lessons/' + storedData2.lesson_id, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' }, // en laravel es muy necesario el headers json para los PUT
                // body: formData
                body: JSON.stringify(storedData2)
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                //Aqui borramos las keys de edicion antes de actualizar la vista
                Object.keys(localStorage).forEach(key => { if (key.includes("lessonData_lesson-form")) { delete localStorage[key]; }});
                // Aquí puedes agregar código para actualizar la interfaz si es necesario
                location.reload();
            })
            .catch(error => {
                console.error(error);
            });
        } else {
            // Disparar fetch con post para crear la lección
            fetch('/api/lessons', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
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

{{-- script de lecciones --}}

{{-- Script de recursos --}}
<script>
    // Función para manejar la creación de recursos
    function handleResourceSubmit(event) {
        event.preventDefault();
        const button = event.target;
        const formId = button.getAttribute('data-form');
        const form = document.getElementById(formId);
        const formData = new FormData(form);

        button.disabled = true;

        fetch('/api/resources', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Recurso creado:', data);
            form.reset();
            location.reload(); // Recarga la página para mostrar el nuevo recurso
        })
        .catch(error => {
            console.error('Error al crear el recurso:', error);
        })
        .finally(() => {
            button.disabled = false;
        });
    }

    // Función para manejar la eliminación de recursos
    function handleResourceDelete(event) {
        event.preventDefault();
        if (!confirm('¿Estás seguro de que quieres eliminar este recurso?')) {
            return;
        }

        const button = event.target;
        const resourceId = button.getAttribute('data-resource-id');

        button.disabled = true;

        fetch(`/api/resources/${resourceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Recurso eliminado:', data);
            // Eliminar el elemento del DOM
            button.closest('li').remove();
        })
        .catch(error => {
            console.error('Error al eliminar el recurso:', error);
        })
        .finally(() => {
            button.disabled = false;
        });
    }

    // Agregar event listeners a los botones de crear y eliminar
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.submit-resource').forEach(button => {
            button.addEventListener('click', handleResourceSubmit);
        });

        document.querySelectorAll('.delete-resource').forEach(button => {
            button.addEventListener('click', handleResourceDelete);
        });
    });
</script>

<script>
    // script que hacen funcionar los tabs
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('a[role="tab"]');
        const tabContents = document.querySelectorAll('div[role="tabpanel"]');
        function hideAllTabContents() {// Función para ocultar todos los contenidos de los tabs
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            tabs.forEach(content => {
                content.classList.remove('text-indigo-600');
            });
        }
        function showActiveTabContent(index) {// Función para mostrar el contenido del tab activo
            hideAllTabContents();
            tabContents[index].classList.remove('hidden');
            tabs[index].classList.add('text-indigo-600');
        }
        tabs.forEach((tab, index) => {// Agregar eventos de clic a los enlaces de los tabs
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                showActiveTabContent(index);
            });
        });
        showActiveTabContent(0);// Mostrar el contenido del primer tab por defecto
    });
</script>