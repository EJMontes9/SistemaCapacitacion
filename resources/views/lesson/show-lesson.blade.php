@vite(['resources/js/courses-view.js'])
<meta name="csrf-token" content="{{ csrf_token() }}">

<x-app-layout>
    <div class="flex flex-col bg" id="dataLesson" data-course="{{$course->id}}" data-lessonId="{{ $thislesson->id }}">
        <div class="w-full mt-4">
            <button onclick="window.history.back()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </button>
            @if($nextLesson)
                <a href="{{ route('courses.showLesson', ['slug' => $course->slug, 'id_lesson' => $nextLesson->id]) }}"
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Siguiente Lección
                </a>
            @endif
        </div>
        <div class="flex flex-row">
            <div class="w-3/4">
                <h1 class="text-2xl  text-gray-900 mb-4">Está viendo la clase: <b> {{$thislesson->name}}</b></h1>
                <div class="py-4 w-full">
                    {!! $thislesson->iframe !!}
                </div>
            </div>
            <div class="flex flex-col w-1/4">
                <div class="w-full">
                    <h2 class="text-2xl  text-gray-900 mb-4">FeedBack</h2>
                    <div class="lesson-content" id="encuesta" data-userId='{{ Auth::user()->id }}'>
                        @if ($hasResponded)
                            <p class="mt-2 text-sm text-gray-600">Ya has respondido a esta encuesta.</p>
                        @endif
                        <!-- El script insertará los botones de la encuesta aquí si es necesario -->
                    </div>
                </div>
                <div class="flex flex-row mt-5">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div
                            class="peer ring-0 bg-rose-400  rounded-full outline-none duration-300 after:duration-500 w-12 h-12
        shadow-md peer-checked:bg-emerald-500  peer-focus:outline-none  after:content-['✖️']
        after:rounded-full after:absolute after:outline-none after:h-10 after:w-10 after:bg-gray-50
        after:top-1 after:left-1 after:flex after:justify-center after:items-center  peer-hover:after:scale-75
        peer-checked:after:content-['✔️'] after:-rotate-180 peer-checked:after:rotate-0">
                        </div>
                    </label>
                    <p class="ml-3">Lección completada</p>
                </div>
            </div>
        </div>
        <div class="w-full">
            <h2 class="text-2xl  text-gray-900 mb-4">Descripción</h2>
            <p class="text-gray-700">{{ $thislesson->description }}</p>
        </div>
        <div class="w-full mt-3">
            <h2 class="text-2xl  text-gray-900 mb-4">Recursos</h2>
            <ul class="grid grid-cols-4 gap-3">
                @foreach ($resources as $resource)
                    <li class="flex items-center bg-gray-100 px-4 py-2 border rounded-lg">
                        @if ($resource->type === 'documento')
                            <i class="fas fa-file-alt mr-2"></i>
                        @elseif ($resource->type === 'imagen')
                            <i class="fas fa-image mr-2"></i>
                        @elseif ($resource->type === 'url')
                            <i class="fas fa-link mr-2"></i>
                        @elseif ($resource->type === 'video')
                            <i class="fas fa-video mr-2"></i>
                        @endif
                        <a href="{{ $resource->url }}" target="_blank">{{ $resource->name }} - {{ $resource->type }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
    @push('scripts')
        {{-- script detector  de encuesta tipo yes/no --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const pathParts = window.location.pathname.split('/');
                const lessonId = pathParts[pathParts.length - 1];
                const hasResponded = {{ json_encode($hasResponded) }};

                if (hasResponded) {
                    document.querySelectorAll('.survey-button').forEach(button => {
                        button.disabled = true;
                        button.classList.add('opacity-50', 'cursor-not-allowed');
                    });
                    const confirmationMessage = document.createElement('p');
                    confirmationMessage.textContent = 'Ya has respondido a esta encuesta.';
                    confirmationMessage.className = 'mt-2 text-sm text-gray-600';
                    document.querySelector('.survey-container').appendChild(confirmationMessage);
                } else {
                    fetch(`/api/check-survey/${lessonId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.hasSurvey && data.surveys.length > 0) {
                                const surveyContainer = document.createElement('div');
                                surveyContainer.className = 'mt-4 space-y-4';

                                data.surveys.forEach(survey => {
                                    const surveyWidget = createSurveyWidget(survey, lessonId);
                                    surveyContainer.appendChild(surveyWidget);
                                });

                                if (surveyContainer.children.length > 0) {
                                    document.querySelector('.lesson-content').appendChild(surveyContainer);
                                }
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            function sendResponse(response, surveyId, lessonId) {
                const userId = document.querySelector('#encuesta').getAttribute('data-userId');
                fetch('/api/survey-responses', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        survey_id: surveyId,
                        lesson_id: lessonId,
                        response: response,
                        user_id: userId
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            console.log('Respuesta enviada:', data.message);
                            updateSurveyUI(response, surveyId);
                        } else {
                            console.error('Error al enviar la respuesta:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (error.errors) {
                            let errorMessage = 'Por favor, corrige los siguientes errores:\n';
                            Object.values(error.errors).forEach(err => {
                                errorMessage += `- ${err[0]}\n`;
                            });
                            alert(errorMessage);
                        }
                    });
            }

            function updateSurveyUI(response, surveyId) {
                const widget = document.querySelector(`#survey_widget_${surveyId}`);
                if (widget) {
                    const yesButton = widget.querySelector(`#thumbsUp_${surveyId}`);
                    const noButton = widget.querySelector(`#thumbsDown_${surveyId}`);

                    // Deshabilitar ambos botones
                    yesButton.disabled = true;
                    noButton.disabled = true;
                    yesButton.classList.add('opacity-50', 'cursor-not-allowed');
                    noButton.classList.add('opacity-50', 'cursor-not-allowed');

                    // Resaltar el botón seleccionado
                    if (response === 'yes') {
                        yesButton.classList.add('bg-green-700');
                        noButton.classList.remove('bg-red-600');
                        noButton.classList.add('bg-red-400');
                    } else {
                        noButton.classList.add('bg-red-700');
                        yesButton.classList.remove('bg-green-600');
                        yesButton.classList.add('bg-green-400');
                    }

                    // Añadir un mensaje de confirmación
                    const confirmationMessage = document.createElement('p');
                    confirmationMessage.textContent = 'Gracias por tu respuesta!';
                    confirmationMessage.className = 'mt-2 text-sm text-gray-600';
                    widget.appendChild(confirmationMessage);
                }
            }

            function createSurveyWidget(survey, lessonId) {
                const widget = document.createElement('div');
                widget.id = `survey_widget_${survey.surveyId}`;
                widget.className = 'p-4 bg-gray-100 rounded-lg mt-4';
                widget.innerHTML = `
        <h3 class="text-lg font-semibold mb-2">${survey.surveyTitle}</h3>
        <div class="flex space-x-4">
            <button id="thumbsUp_${survey.surveyId}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded ${survey.response === 'yes' ? 'opacity-50 cursor-not-allowed' : ''}">
                <i class="fas fa-thumbs-up"></i> Sí
            </button>
            <button id="thumbsDown_${survey.surveyId}" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded ${survey.response === 'no' ? 'opacity-50 cursor-not-allowed' : ''}">
                <i class="fas fa-thumbs-down"></i> No
            </button>
        </div>
    `;

                if (!survey.hasResponded) {
                    widget.querySelector(`#thumbsUp_${survey.surveyId}`).addEventListener('click', () => sendResponse('yes', survey.surveyId, lessonId));
                    widget.querySelector(`#thumbsDown_${survey.surveyId}`).addEventListener('click', () => sendResponse('no', survey.surveyId, lessonId));
                } else {
                    updateSurveyUI(survey.response, survey.surveyId);
                }

                return widget;
            }

        </script>
    @endpush

    {{-- script de widget rating --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const courseId = parseInt(document.querySelector('#dataLesson').getAttribute('data-course'));
            const lessonId = parseInt(document.querySelector('#dataLesson').getAttribute('data-lessonId'));
            const userId = parseInt(document.querySelector('#encuesta').getAttribute('data-userId'));
            const ratingContainer = document.getElementById('lesson-rating');

            if (!ratingContainer) return;

            fetch(`/api/course/${courseId}/lesson-ratings`)
                .then(response => response.json())
                .then(data => {
                    if (data.data && Array.isArray(data.data)) {
                        const lessonRating = data.data.find(item => item.lesson_id === lessonId);
                        if (lessonRating) {
                            showRatingWidget(lessonRating);
                        } else {
                            ratingContainer.innerHTML = '<p>No hay calificaciones disponibles para esta lección.</p>';
                        }
                    } else {
                        ratingContainer.innerHTML = '<p>No se encontraron datos de calificaciones.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching ratings:', error);
                    ratingContainer.innerHTML = '<p>Error al cargar las calificaciones.</p>';
                });

            function showRatingWidget(ratingData) {
                const {averageRating, ratingCount, userRating, survey_id} = ratingData;
                ratingContainer.innerHTML = `
                <div class="flex flex-col items-center">
                    <div class="flex items-center space-x-1">
                        ${[1, 2, 3, 4, 5].map(star => `
                            <button type="button" class="star ${star <= Math.round(averageRating) ? 'text-yellow-400' : 'text-gray-300'}" data-value="${star}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </button>
                        `).join('')}
                    </div>
                    <p class="text-sm mt-2">Promedio: ${averageRating.toFixed(1)} (${ratingCount} valoraciones)</p>
                    <p class="text-sm hidden">Tu calificación: ${userRating ?? 'No has calificado aún'}</p>
                </div>
            `;

                ratingContainer.querySelectorAll('.star').forEach(star => {
                    star.addEventListener('click', function () {
                        const rating = this.dataset.value;
                        submitRating(rating, survey_id);
                    });
                });
            }

            function submitRating(rating, surveyId) {
                fetch('/api/lesson-ratings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        lesson_id: lessonId,
                        survey_id: surveyId,
                        rating: rating
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Rating submitted:', data);
                        showRatingWidget({
                            averageRating: data.averageRating,
                            ratingCount: data.ratingCount,
                            userRating: data.userRating,
                            survey_id: surveyId
                        });
                    })
                    .catch(error => console.error('Error submitting rating:', error));
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.querySelector('input[type="checkbox"]');
            const lessonId = document.querySelector('#dataLesson').getAttribute('data-lessonId');
            const userId = document.querySelector('#encuesta').getAttribute('data-userId');

            // Fetch the current completion status
            fetch(`/api/lesson-user/${userId}/${lessonId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.completed) {
                        checkbox.checked = true;
                    }
                })
                .catch(error => console.error('Error fetching completion status:', error));

            checkbox.addEventListener('change', function () {
                const currentTimestamp = new Date().toISOString().slice(0, 19).replace('T', ' ');

                if (this.checked) {
                    fetch('/api/lesson-user', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            lesson_id: lessonId,
                            created_at: currentTimestamp,
                            updated_at: currentTimestamp
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Data inserted successfully:', data.message);
                            } else {
                                console.error('Error inserting data:', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            fetch('/api/lesson-user')
                                .then(response => response.text())
                                .then(text => console.error('Response text:', text));
                        });
                } else {
                    fetch(`/api/lesson-user/${userId}/${lessonId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Data deleted successfully:', data.message);
                            } else {
                                console.error('Error deleting data:', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            fetch(`/api/lesson-user/${userId}/${lessonId}`)
                                .then(response => response.text())
                                .then(text => console.error('Response text:', text));
                        });
                }
            });
        });
    </script>
</x-app-layout>
