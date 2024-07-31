<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Gestion de encuentas de satisfacción</h2>
                {{-- <a href="{{ route('surveys.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Crear Nueva Encuesta</a> --}}
                <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block"><i class="fa fa-plus"></i> Crear Encuesta</button>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Título</th>
                            <th class="py-2 px-4 border-b">Categoría</th>
                            <th class="py-2 px-4 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="surveys-list">
                        <!-- Las encuestas se cargarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de edición -->
    <div id="editModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Editar Encuesta
                    </h3>
                    <div class="mt-2">
                        <form id="edit-survey-form">
                            <input type="hidden" id="edit-survey-id">
                            <div class="mb-4">
                                <label for="edit-title" class="block text-gray-700 text-sm font-bold mb-2">Título:</label>
                                <input type="text" id="edit-title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                            <div class="mb-4">
                                <label for="edit-description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                                <textarea id="edit-description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="edit-category" class="block text-gray-700 text-sm font-bold mb-2">Categorías:</label>
                                <select id="edit-category" name="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="lesson">Lección</option>
                                    <option value="course">Curso</option>
                                </select>
                            </div>
                            <div id="edit-course-select-container" class="mb-4 hidden">
                                <label for="edit-course-id" class="block text-gray-700 text-sm font-bold mb-2">Curso:</label>
                                <select id="edit-course-id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Seleccione un curso</option>
                                </select>
                            </div>
                            <div id="edit-section-select-container" class="mb-4 hidden">
                                <label for="edit-section-id" class="block text-gray-700 text-sm font-bold mb-2">Sección:</label>
                                <select id="edit-section-id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Seleccione una sección</option>
                                </select>
                            </div>
                            <div id="edit-lesson-select-container" class="mb-4 hidden">
                                <label for="edit-lesson-id" class="block text-gray-700 text-sm font-bold mb-2">Lección:</label>
                                <select id="edit-lesson-id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Seleccione una lección</option>
                                </select>
                            </div>
                            <input type="hidden" id="edit-target_id" name="target_id">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tipos de preguntas:</label>
                                <div>
                                    <input type="checkbox" id="edit-has_yes_no" name="has_yes_no" value="1">
                                    <label for="edit-has_yes_no">Sí/No</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="edit-has_rating" name="has_rating" value="1">
                                    <label for="edit-has_rating">Calificación (1-5)</label>
                                </div>
                                <div class="hidden">
                                    <input type="checkbox" id="edit-has_comment" name="has_comment" value="1">
                                    <label for="edit-has_comment">Comentario</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="saveEditButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar cambios
                    </button>
                    <button type="button" id="closeModalButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="createModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Crear Encuesta
                    </h3>
                    <div class="mt-2">
                        <form id="create-survey-form">
                            <div class="mb-4">
                                <label for="create-title" class="block text-gray-700 text-sm font-bold mb-2">Título:</label>
                                <input type="text" id="create-title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                            <div class="mb-4">
                                <label for="create-description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                                <textarea id="create-description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="create-category" class="block text-gray-700 text-sm font-bold mb-2">Categoría:</label>
                                <select id="create-category" name="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">Seleccione una categoría</option>
                                    <option value="lesson">Lección</option>
                                    <option value="course">Curso</option>
                                </select>
                            </div>
                            <div id="create-course-select-container" class="mb-4 hidden">
                                <label for="create-course-id" class="block text-gray-700 text-sm font-bold mb-2">Curso:</label>
                                <select id="create-course-id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Seleccione un curso</option>
                                </select>
                            </div>
                            <div id="create-section-select-container" class="mb-4 hidden">
                                <label for="create-section-id" class="block text-gray-700 text-sm font-bold mb-2">Sección:</label>
                                <select id="create-section-id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Seleccione una sección</option>
                                </select>
                            </div>
                            <div id="create-lesson-select-container" class="mb-4 hidden">
                                <label for="create-lesson-id" class="block text-gray-700 text-sm font-bold mb-2">Lección:</label>
                                <select id="create-lesson-id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Seleccione una lección</option>
                                </select>
                            </div>
                            <input type="hidden" id="create-target_id" name="target_id">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tipos de preguntas:</label>
                                <div>
                                    <input type="radio" id="create-has_yes_no" name="has_yes_no" value="1">
                                    <label for="create-has_yes_no">Sí/No</label>
                                </div>
                                <div>
                                    <input type="radio" id="create-has_rating" name="has_yes_no" value="1">
                                    <label for="create-has_rating">Calificación (1-5)</label>
                                </div>
                                <div class="hidden">
                                    <input type="checkbox" id="create-has_comment" name="has_comment" value="1">
                                    <label for="create-has_comment">Comentario</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="saveCreateButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Crear Encuesta
                    </button>
                    <button type="button" id="closeModalButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadSurveys();
        setupEventListeners();
    });

    function setupEventListeners() {
        setupFormListeners('edit');
        setupFormListeners('create');

        document.querySelectorAll('#closeModalButton').forEach(button => {
            button.addEventListener('click', closeModals);
        });
        document.getElementById('saveEditButton').addEventListener('click', saveEditSurvey);
        document.getElementById('saveCreateButton').addEventListener('click', saveCreateSurvey);
    }

    function setupFormListeners(prefix) {
        const categorySelect = document.getElementById(`${prefix}-category`);
        const courseSelect = document.getElementById(`${prefix}-course-id`);
        const sectionSelect = document.getElementById(`${prefix}-section-id`);
        const lessonSelect = document.getElementById(`${prefix}-lesson-id`);

        if (categorySelect) {
            categorySelect.addEventListener('change', () => handleCategoryChange(prefix));
        }
        if (courseSelect) {
            courseSelect.addEventListener('change', () => handleCourseChange(prefix));
        }
        if (sectionSelect) {
            sectionSelect.addEventListener('change', () => handleSectionChange(prefix));
        }
        if (lessonSelect) {
            lessonSelect.addEventListener('change', () => {
                document.getElementById(`${prefix}-target_id`).value = lessonSelect.value;
            });
        }
    }

    function handleCategoryChange(prefix) {
        const category = document.getElementById(`${prefix}-category`).value;
        const containers = ['course', 'section', 'lesson'].map(type => 
            document.getElementById(`${prefix}-${type}-select-container`)
        );

        containers.forEach(container => {
            if (container) container.classList.add('hidden');
        });

        if (['course', 'section', 'lesson'].includes(category)) {
            const courseContainer = document.getElementById(`${prefix}-course-select-container`);
            if (courseContainer) {
                courseContainer.classList.remove('hidden');
            }
            loadCourses(prefix);
        }
    }

    function handleCourseChange(prefix) {
        const category = document.getElementById(`${prefix}-category`).value;
        const courseId = document.getElementById(`${prefix}-course-id`).value;
        const targetIdInput = document.getElementById(`${prefix}-target_id`);

        if (category === 'course') {
            if (targetIdInput) targetIdInput.value = courseId;
        } else if (['section', 'lesson'].includes(category)) {
            const sectionContainer = document.getElementById(`${prefix}-section-select-container`);
            if (sectionContainer) {
                sectionContainer.classList.remove('hidden');
            }
            loadSections(prefix, courseId);
        }
    }

    function handleSectionChange(prefix) {
        const category = document.getElementById(`${prefix}-category`).value;
        const sectionId = document.getElementById(`${prefix}-section-id`).value;
        const targetIdInput = document.getElementById(`${prefix}-target_id`);

        if (category === 'section') {
            if (targetIdInput) targetIdInput.value = sectionId;
        } else if (category === 'lesson') {
            const lessonContainer = document.getElementById(`${prefix}-lesson-select-container`);
            if (lessonContainer) {
                lessonContainer.classList.remove('hidden');
            }
            loadLessons(prefix, sectionId);
        }
    }

    function loadSurveys() {
        fetch('/api/surveys')
            .then(response => response.json())
            .then(surveys => {
                const surveysList = document.getElementById('surveys-list');
                surveysList.innerHTML = surveys.map(survey => `
                    <tr>
                        <td class="py-2 px-4 border-b">${survey.title}</td>
                        <td class="py-2 px-4 border-b">${survey.category}</td>
                        <td class="py-2 px-4 border-b">
                            <button onclick="openEditModal(${survey.id})" class="text-blue-500 hover:text-blue-700 mr-2">Editar</button>
                            <button onclick="deleteSurvey(${survey.id})" class="text-red-500 hover:text-red-700">Eliminar</button>
                        </td>
                    </tr>
                `).join('');
            });
    }

    function loadCourses(prefix) {
        fetch('/api/courses/list')
            .then(response => response.json())
            .then(courses => {
                const select = document.getElementById(`${prefix}-course-id`);
                if (select) {
                    select.innerHTML = '<option value="">Seleccione un curso</option>' + 
                        courses.map(course => `<option value="${course.id}">${course.title}</option>`).join('');
                }
            });
    }

    function loadSections(prefix, courseId) {
        fetch(`/api/courses/${courseId}/sections`)
            .then(response => response.json())
            .then(sections => {
                const select = document.getElementById(`${prefix}-section-id`);
                if (select) {
                    select.innerHTML = '<option value="">Seleccione una sección</option>' + 
                        sections.map(section => `<option value="${section.id}">${section.name}</option>`).join('');
                }
            });
    }

    function loadLessons(prefix, sectionId) {
        fetch(`/api/sections/${sectionId}/lessons`)
            .then(response => response.json())
            .then(lessons => {
                const select = document.getElementById(`${prefix}-lesson-id`);
                if (select) {
                    select.innerHTML = '<option value="">Seleccione una lección</option>' + 
                        lessons.map(lesson => `<option value="${lesson.id}">${lesson.name}</option>`).join('');
                }
            });
    }

    async function openEditModal(surveyId) {
        const survey = await fetch(`/api/surveys/${surveyId}`).then(response => response.json());
        
        const elements = [
            { id: 'edit-survey-id', property: 'value', value: survey.id },
            { id: 'edit-title', property: 'value', value: survey.title },
            { id: 'edit-description', property: 'value', value: survey.description },
            { id: 'edit-category', property: 'value', value: survey.category },
            { id: 'edit-target_id', property: 'value', value: survey.target_id },
            { id: 'edit-has_yes_no', property: 'checked', value: survey.has_yes_no },
            { id: 'edit-has_rating', property: 'checked', value: survey.has_rating },
            { id: 'edit-has_comment', property: 'checked', value: survey.has_comment },
        ];

        elements.forEach(({ id, property, value }) => {
            const element = document.getElementById(id);
            if (element) {
                element[property] = value;
            }
        });
        
        await loadRelatedData('edit', survey.category, survey.target_id);
        
        const editModal = document.getElementById('editModal');
        if (editModal) {
            editModal.classList.remove('hidden');
        }
    }

    async function loadRelatedData(prefix, category, targetId) {
        const courseSelect = document.getElementById(`${prefix}-course-id`);
        const sectionSelect = document.getElementById(`${prefix}-section-id`);
        const lessonSelect = document.getElementById(`${prefix}-lesson-id`);

        if (courseSelect) courseSelect.innerHTML = '<option value="">Cargando...</option>';
        if (sectionSelect) sectionSelect.innerHTML = '<option value="">Cargando...</option>';
        if (lessonSelect) lessonSelect.innerHTML = '<option value="">Cargando...</option>';

        const courses = await fetch('/api/courses/list').then(response => response.json());
        if (courseSelect) populateSelect(courseSelect, courses, 'id', 'title');

        if (category === 'course') {
            if (courseSelect) courseSelect.value = targetId;
        } else if (category === 'section' || category === 'lesson') {
            const courseId = await getCourseIdFromTarget(category, targetId);
            if (courseSelect) courseSelect.value = courseId;
            
            const sections = await fetch(`/api/courses/${courseId}/sections`).then(response => response.json());
            if (sectionSelect) populateSelect(sectionSelect, sections, 'id', 'name');

            if (category === 'section') {
                if (sectionSelect) sectionSelect.value = targetId;
            } else if (category === 'lesson') {
                const sectionId = await getSectionIdFromLesson(targetId);
                if (sectionSelect) sectionSelect.value = sectionId;

                const lessons = await fetch(`/api/sections/${sectionId}/lessons`).then(response => response.json());
                if (lessonSelect) {
                    populateSelect(lessonSelect, lessons, 'id', 'name');
                    lessonSelect.value = targetId;
                }
            }
        }

        const categorySelect = document.getElementById(`${prefix}-category`);
        if (categorySelect) {
            categorySelect.dispatchEvent(new Event('change'));
        }
    }

    function populateSelect(select, options, valueKey, textKey) {
        select.innerHTML = options.map(option => 
            `<option value="${option[valueKey]}">${option[textKey]}</option>`
        ).join('');
        select.insertAdjacentHTML('afterbegin', '<option value="">Seleccione una opción</option>');
    }

    async function getCourseIdFromTarget(category, targetId) {
        if (category === 'section') {
            const section = await fetch(`/api/sections/${targetId}`).then(response => response.json());
            return section.course_id;
        } else if (category === 'lesson') {
            const lesson = await fetch(`/api/lessons/${targetId}`).then(response => response.json());
            const section = await fetch(`/api/sections/${lesson.section_id}`).then(response => response.json());
            return section.course_id;
        }
    }

    async function getSectionIdFromLesson(lessonId) {
        const lesson = await fetch(`/api/lessons/${lessonId}`).then(response => response.json());
        return lesson.section_id;
    }

    function saveEditSurvey() {
        const surveyId = document.getElementById('edit-survey-id').value;
        const formData = new FormData(document.getElementById('edit-survey-form'));
        const data = Object.fromEntries(formData.entries());

        // Convierte los checkboxes a booleanos
        data.has_yes_no = !!data.has_yes_no;
        data.has_rating = !!data.has_rating;
        data.has_comment = !!data.has_comment;

        // Asegúrate de que target_id sea un número
        data.target_id = parseInt(data.target_id);

        fetch(`/api/surveys/${surveyId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                });
            }
            return response.json();
        })
        .then(survey => {
            alert('Encuesta actualizada con éxito');
            document.getElementById('editModal').classList.add('hidden');
            loadSurveys();
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Error al actualizar la encuesta: ${error.message}`);
        });
    }

    function saveCreateSurvey() {
        const formData = new FormData(document.getElementById('create-survey-form'));
        const data = Object.fromEntries(formData.entries());

        // Convierte los checkboxes a booleanos
        data.has_yes_no = !!data.has_yes_no;
        data.has_rating = !!data.has_rating;
        data.has_comment = !!data.has_comment;

        // Asegúrate de que target_id sea un número
        data.target_id = parseInt(data.target_id);

        fetch('/api/surveys', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                });
            }
            return response.json();
        })
        .then(survey => {
            alert('Encuesta creada con éxito');
            document.getElementById('createModal').classList.add('hidden');
            loadSurveys();
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Error al crear la encuesta: ${error.message}`);
        });
    }

    function deleteSurvey(id) {
        if (confirm('¿Estás seguro de querer eliminar esta encuesta?')) {
            fetch(`/api/surveys/${id}`, { 
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    loadSurveys();
                } else {
                    alert('Error al eliminar la encuesta');
                }
            });
        }
    }

    function openCreateModal() {
        resetForm('create-survey-form');
        document.getElementById('createModal').classList.remove('hidden');
    }

    function resetForm(formId) {
        document.getElementById(formId).reset();
        const categorySelect = document.getElementById(`${formId.split('-')[0]}-category`);
        if (categorySelect) {
            categorySelect.dispatchEvent(new Event('change'));
        }
    }

    function closeModals() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('createModal').classList.add('hidden');
    }
</script>
</x-app-layout>