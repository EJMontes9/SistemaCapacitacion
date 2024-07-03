<meta name="csrf-token" content="{{ csrf_token() }}">
<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Gestor de Encuestas</h1>
        
        <form id="surveyForm" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <input type="hidden" id="surveyId">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Título
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" name="title" type="text" placeholder="Título de la encuesta">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Descripción
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" placeholder="Descripción de la encuesta"></textarea>
            </div>
            <div id="questions" class="mb-4">
                <!-- Las preguntas se añadirán aquí dinámicamente -->
            </div>
            <div class="mb-4">
                <button type="button" id="addQuestion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Añadir Pregunta
                </button>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Guardar Encuesta
                </button>
            </div>
        </form>

        <div id="surveyList" class="mt-8">
            <!-- Las encuestas se listarán aquí -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const surveyForm = document.getElementById('surveyForm');
            const addQuestionBtn = document.getElementById('addQuestion');
            const questionsContainer = document.getElementById('questions');
            const surveyList = document.getElementById('surveyList');

            let questionCount = 0;

            function addQuestionField() {
                questionCount++;
                const questionDiv = document.createElement('div');
                questionDiv.className = 'mb-4 p-4 border rounded';
                questionDiv.innerHTML = `
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Pregunta ${questionCount}
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="question_${questionCount}" placeholder="Texto de la pregunta">
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Tipo de Pregunta
                        </label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="type_${questionCount}">
                            <option value="text">Texto</option>
                            <option value="rating">Calificación</option>
                            <option value="boolean">Sí/No</option>
                        </select>
                    </div>
                `;
                questionsContainer.appendChild(questionDiv);
            }

            addQuestionBtn.addEventListener('click', addQuestionField);

            surveyForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(surveyForm);
                const surveyData = {
                    title: formData.get('title'),
                    description: formData.get('description'),
                    questions: []
                };

                for (let i = 1; i <= questionCount; i++) {
                    const question = formData.get(`question_${i}`);
                    const type = formData.get(`type_${i}`);
                    if (question) {
                        surveyData.questions.push({ question, type });
                    }
                }

                const surveyId = document.getElementById('surveyId').value;
                const url = surveyId ? `/surveys/${surveyId}` : '/surveys';
                const method = surveyId ? 'PUT' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify(surveyData)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Encuesta guardada con éxito');
                    surveyForm.reset();
                    document.getElementById('surveyId').value = '';
                    questionsContainer.innerHTML = '';
                    questionCount = 0;
                    loadSurveys();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al guardar la encuesta: ' + error.message);
                });
            });

            function loadSurveys() {
                fetch('/surveys')
                    .then(response => response.json())
                    .then(surveys => {
                        surveyList.innerHTML = '<h2 class="text-xl font-bold mb-4">Encuestas Existentes</h2>';
                        surveys.forEach(survey => {
                            const surveyElement = document.createElement('div');
                            surveyElement.className = 'bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4';
                            surveyElement.innerHTML = `
                                <h3 class="text-lg font-bold">${survey.title}</h3>
                                <p class="mb-2">${survey.description || 'Sin descripción'}</p>
                                <button class="edit-survey bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2" data-id="${survey.id}">Editar</button>
                                <button class="delete-survey bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" data-id="${survey.id}">Eliminar</button>
                            `;
                            surveyList.appendChild(surveyElement);
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar encuestas:', error);
                        alert('Error al cargar las encuestas');
                    });
            }

            surveyList.addEventListener('click', function(e) {
                if (e.target.classList.contains('edit-survey')) {
                    const surveyId = e.target.getAttribute('data-id');
                    fetch(`/surveys/${surveyId}`)
                        .then(response => response.json())
                        .then(survey => {
                            document.getElementById('surveyId').value = survey.id;
                            document.getElementById('title').value = survey.title;
                            document.getElementById('description').value = survey.description;
                            questionsContainer.innerHTML = '';
                            survey.questions.forEach((q, index) => {
                                addQuestionField();
                                document.querySelector(`input[name="question_${index + 1}"]`).value = q.question;
                                document.querySelector(`select[name="type_${index + 1}"]`).value = q.type;
                            });
                        })
                        .catch(error => {
                            console.error('Error al cargar la encuesta:', error);
                            alert('Error al cargar la encuesta para editar');
                        });
                } else if (e.target.classList.contains('delete-survey')) {
                    if (confirm('¿Estás seguro de que quieres eliminar esta encuesta?')) {
                        const surveyId = e.target.getAttribute('data-id');
                        fetch(`/surveys/${surveyId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error al eliminar la encuesta');
                            }
                            loadSurveys();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error al eliminar la encuesta: ' + error.message);
                        });
                    }
                }
            });

            // Cargar encuestas al inicio
            loadSurveys();
        });
    </script>
</x-app-layout>