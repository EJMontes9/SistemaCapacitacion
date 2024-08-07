@vite(['resources/js/courses-view.js'])
<meta name="csrf-token" content="{{ csrf_token() }}">
<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <h1 class="text-2xl font-semibold text-gray-900 mb-4">Ahora agreguemos secciones
                    a: {{ $course->title }}</h1>
                <img class="w-full h-48 object-cover rounded-lg mb-4" src="../../images/courses/{{ $course->image }}"
                     alt="">
                <div class="mt-4">
                    <h3 id="agregar-secciones" class="text-lg font-semibold text-gray-600">Agrega las secciones que
                        necesites en el siguiente formulario</h3>
                </div>
                <div id="form-secciones" class="mt-4">
                    <input type="text" id="nombre-seccion" class="bg-gray-100 text-gray-900 py-2 px-4 w-full rounded-md"
                           placeholder="Nombre de la nueva sección">
                    <button id="guardar-seccion"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full mt-2">
                        Agregar Sección
                    </button>
                    <a href="{{route('courses.show',$course->slug)}}" id="finalizar-curso"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 flex justify-center rounded w-100 mt-4">Finalizar</a>
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-0">
                    <ul id="listado-secciones" class="p-0">
                    </ul>
                    <x-course.list-section-paso2-view :section="$section" :resources="$resources" :lesson="$lesson"
                                                      :course="$course" :evaluation="$evaluation"/>
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
    Object.keys(localStorage).forEach(key => {
        if (key.includes("lessonData_lesson-form")) {
            delete localStorage[key];
        }
    });

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

            if (!acordeonCerrado) { //cada vez que se abre o cierra un acordeon cambiamos el estado para que se despliegue correctamente en cada edit
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
            ['input', 'change'].forEach(eventType => {
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
        if (!canSubmit) return;

        canSubmit = false;
        event.target.disabled = true;

        const formId = event.target.dataset.form;
        const form = document.getElementById(formId);
        const formData = new FormData(form);

        const storedData = JSON.parse(localStorage.getItem(`lessonData_${formId}`));

        if (!storedData) {
            // Si no se encuentra id en el formulario de la lección, creará una nueva
            console.log('Datos del formulario:', Object.fromEntries(formData.entries()));
            fetch('/api/lessons', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Lección creada:', data);
                    location.reload();
                })
                .catch(error => {
                    console.error('Error al crear la lección:', error);
                });
            return;
        }

        for (const [key, value] of Object.entries(storedData)) {
            formData.append(key, value);
        }

        console.log('Datos del formulario:', Object.fromEntries(formData.entries()));

        if (storedData.lesson_id !== '') {
            // Editar la lección existente
            fetch(`/api/lessons/${storedData.lesson_id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(storedData)
            })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Lección actualizada:', data);
                    Object.keys(localStorage).forEach(key => {
                        if (key.includes("lessonData_lesson-form")) {
                            delete localStorage[key];
                        }
                    });
                    location.reload();
                })
                .catch(error => {
                    console.error('Error al actualizar la lección:', error);
                });
        } else {
            // Crear una nueva lección
            fetch('/api/lessons', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Lección creada:', data);
                    location.reload();
                })
                .catch(error => {
                    console.error('Error al crear la lección:', error);
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
    document.addEventListener('DOMContentLoaded', () => {
        // Manejar cambios en el tipo de recurso
        document.body.addEventListener('change', function (e) {
            if (e.target && e.target.name === 'type') {
                const form = e.target.closest('form');
                const fileInput = form.querySelector('.file-input');
                const urlInput = form.querySelector('.url-input');

                if (e.target.value === 'documento' || e.target.value === 'imagen') {
                    fileInput.classList.remove('hidden');
                    urlInput.classList.add('hidden');
                } else if (e.target.value === 'url' || e.target.value === 'video') {
                    urlInput.classList.remove('hidden');
                    fileInput.classList.add('hidden');
                } else {
                    fileInput.classList.add('hidden');
                    urlInput.classList.add('hidden');
                }
            }
        });

        // Agregar event listener para el botón de crear recurso
        document.body.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('submit-resource')) {
                handleResourceSubmit(e);
            }
        });

        // Agregar event listener para los botones de eliminar recurso
        document.body.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('delete-resource')) {
                handleResourceDelete(e);
            }
        });
    });

    function handleResourceDelete(event) {
        event.preventDefault();
        if (!confirm('¿Estás seguro de que quieres eliminar este recurso?')) {
            return;
        }

        const button = event.currentTarget;
        const resourceId = button.getAttribute('data-resource-id');

        button.disabled = true;

        fetch(`/api/resources/${resourceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Recurso eliminado:', data);
                button.closest('li').remove();
            })
            .catch(error => {
                console.error('Error al eliminar el recurso:', error);
                alert('Error al eliminar el recurso: ' + error.message);
            })
            .finally(() => {
                button.disabled = false;
            });
    }

</script>

<script>
    // script que hacen funcionar los tabs
    // document.addEventListener('DOMContentLoaded', function() {
    //     const tabs = document.querySelectorAll('a[role="tab"]');
    //     const tabContents = document.querySelectorAll('div[role="tabpanel"]');
    //     function hideAllTabContents() {// Función para ocultar todos los contenidos de los tabs
    //         tabContents.forEach(content => {
    //             content.classList.add('hidden');
    //         });
    //         tabs.forEach(content => {
    //             content.classList.remove('text-indigo-600');
    //         });
    //     }
    //     function showActiveTabContent(index) {// Función para mostrar el contenido del tab activo
    //         hideAllTabContents();
    //         tabContents[index].classList.remove('hidden');
    //         tabs[index].classList.add('text-indigo-600');
    //     }
    //     tabs.forEach((tab, index) => {// Agregar eventos de clic a los enlaces de los tabs
    //         tab.addEventListener('click', (e) => {
    //             e.preventDefault();
    //             showActiveTabContent(index);
    //         });
    //     });
    //     showActiveTabContent(0);// Mostrar el contenido del primer tab por defecto
    // });
</script>

{{-- scripts de procesamiento de recursos en lecciones --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Cargar recursos para cada lección
        document.querySelectorAll('.resources-list').forEach(resourceList => {
            const lessonId = resourceList.getAttribute('data-lesson-id');
            fetch(`/api/lessons/${lessonId}/resources`)
                .then(response => response.json())
                .then(resources => {
                    resourceList.innerHTML = resources.map(resource => `
                    <li>
                        <div class="flex flex-row justify-between items-center border-2 py-1 my-2 bg-green-200 bg-opacity-50 rounded-xl">
                            <div class="flex items-center ml-4">
                                <a href="${resource.url}" target="_blank" class="flex items-center">
                                    <i class="fa-solid fa-file-alt mr-2"></i>
                                    <span>${resource.name}</span>
                                </a>
                                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-${getResourceTypeColor(resource.type)}">${resource.type}</span>
                            </div>
                            <div class="px-3 flex flex-row justify-center items-center">
                                <button class="delete-resource mx-0 p-0 my-auto" data-resource-id="${resource.id}">
                                    <i class="fa-solid fa-trash text-red-600"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                `).join('');
                });
        });

        // Función para obtener el color del tipo de recurso
        function getResourceTypeColor(type) {
            switch (type) {
                case 'documento':
                    return 'blue-100 text-blue-600';
                case 'imagen':
                    return 'green-100 text-green-600';
                case 'url':
                    return 'green-400 bg-opacity-50 text-green-600';
                case 'video':
                    return 'red-100 text-red-600';
                default:
                    return 'gray-100 text-gray-600';
            }
        }

        // Manejar cambios en el tipo de recurso
        document.body.addEventListener('change', function (e) {
            if (e.target && e.target.name === 'type') {
                const form = e.target.closest('form');
                const fileInput = form.querySelector('input[name="file"]');
                const urlInput = form.querySelector('input[name="url"]');

                if (e.target.value === 'documento' || e.target.value === 'imagen') {
                    fileInput.classList.remove('hidden');
                    urlInput.classList.add('hidden');
                } else if (e.target.value === 'url' || e.target.value === 'video') {
                    urlInput.classList.remove('hidden');
                    fileInput.classList.add('hidden');
                } else {
                    fileInput.classList.add('hidden');
                    urlInput.classList.add('hidden');
                }
            }
        });

        document.body.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('submit-resource')) {
                e.preventDefault();
                const button = e.target;
                const form = document.getElementById(button.getAttribute('data-form'));
                const formData = new FormData(form);

                // Deshabilitar el botón para evitar múltiples envíos
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
                        console.log('Recurso creado con ID:', data.id); // Console log después de crear el recurso
                        // Recargar la lista de recursos
                        const lessonId = form.querySelector('input[name="lesson_id"]').value;
                        const resourceList = document.querySelector(`.resources-list[data-lesson-id="${lessonId}"]`);
                        fetch(`/api/lessons/${lessonId}/resources`)
                            .then(response => response.json())
                            .then(resources => {
                                resourceList.innerHTML = resources.map(resource => `
                        <li>
                            <div class="flex flex-row justify-between items-center border-2 py-1 my-2 bg-green-200 bg-opacity-50 rounded-xl">
                                <div class="flex items-center ml-4">
                                    <a href="${resource.url}" target="_blank" class="flex items-center">
                                        <i class="fa-solid fa-file-alt mr-2"></i>
                                        <span>${resource.name}</span>
                                    </a>
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-${getResourceTypeColor(resource.type)}">${resource.type}</span>
                                </div>
                                <div class="px-3 flex flex-row justify-center items-center">
                                    <button class="delete-resource mx-0 p-0 my-auto" data-resource-id="${resource.id}">
                                        <i class="fa-solid fa-trash text-red-600"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                    `).join('');
                            });
                        form.reset();
                    })
                    .catch(error => {
                        console.error('Error al crear el recurso:', error);
                    })
                    .finally(() => {
                        // Habilitar el botón después de la respuesta
                        button.disabled = false;
                    });
            }
        });

        // Manejar eliminación de recursos
        document.body.addEventListener('click', function (e) {
            const button = e.target.closest('.delete-resource');
            if (button) {
                e.preventDefault();
                if (!confirm('¿Estás seguro de que quieres eliminar este recurso?')) {
                    return;
                }

                const resourceId = button.getAttribute('data-resource-id');

                fetch(`/api/resources/${resourceId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Recurso eliminado:', data);
                        // Eliminar el elemento del DOM
                        button.closest('li').remove();
                    })
                    .catch(error => {
                        console.error('Error al eliminar el recurso:', error);
                        alert('Error al eliminar el recurso: ' + error.message);
                    });
            }
        });
    });
</script>