@vite(['resources/js/courses-view.js'])
<meta name="csrf-token" content="{{ csrf_token() }}">

<x-app-layout>
    <div class="flex flex-column">
        <div class="w-full mt-9">
            <h1 class="text-2xl font-semibold text-gray-900 mb-4">{{$thislesson->name}}</h1>
            <div class="p-4 w-[500px] h-[400px]">
                {!! $thislesson->iframe !!}
            </div>

            <div class="w-full">
                <x-course.list-section-view :section="$section" :lesson="$lesson" :course="$course" :evaluation="$evaluation"/>
            </div>
        </div>

        <div class="lesson-content" id="encuesta" data-userId='{{ Auth::user()->id }}'>
            
            <!-- El script insertará los botones de la encuesta aquí si es necesario -->
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const pathParts = window.location.pathname.split('/');
                const lessonId = pathParts[pathParts.length - 1];

                fetch(`/api/check-survey/${lessonId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.hasSurvey) {
                            const textoSurvey = data.surveyTitle;                            ;
                            const surveyContainer = document.createElement('div');
                            surveyContainer.className = 'mt-4 p-4 bg-gray-100 rounded-lg';
                            surveyContainer.innerHTML = `
                                <h3 class="text-lg font-semibold mb-2">${textoSurvey}</h3>
                                <div class="flex space-x-4">
                                    <button id="thumbsUp" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded ${data.response === 'yes' ? 'opacity-50 cursor-not-allowed' : ''}">
                                        <i class="fas fa-thumbs-up"></i> Sí
                                    </button>
                                    <button id="thumbsDown" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded ${data.response === 'no' ? 'opacity-50 cursor-not-allowed' : ''}">
                                        <i class="fas fa-thumbs-down"></i> No
                                    </button>
                                </div>
                            `;

                            document.querySelector('.lesson-content').appendChild(surveyContainer);

                            if (!data.hasResponded) {
                                document.getElementById('thumbsUp').addEventListener('click', () => sendResponse('yes', data.surveyId, lessonId));
                                document.getElementById('thumbsDown').addEventListener('click', () => sendResponse('no', data.surveyId, lessonId));
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            function sendResponse(response, surveyId, lessonId) {
                userId =   document.querySelector('#encuesta').getAttribute('data-userId');
                // console.log('el user Id es '+userId);
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
                        user_id : userId
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
                        // Actualizar la UI para reflejar la respuesta enviada
                        updateSurveyUI(response);
                    } else {
                        console.error('Error al enviar la respuesta:', data.message);
                        // alert('Hubo un problema al enviar tu respuesta. Por favor, intenta de nuevo.');
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
                    } else {
                        // alert('Hubo un problema al enviar tu respuesta. Por favor, intenta de nuevo.');
                    }
                });
            }

            function updateSurveyUI(response) {
                const thumbsUp = document.getElementById('thumbsUp');
                const thumbsDown = document.getElementById('thumbsDown');

                thumbsUp.classList.toggle('bg-green-600', response === 'yes');
                thumbsDown.classList.toggle('bg-red-600', response === 'no');

                thumbsUp.disabled = true;
                thumbsDown.disabled = true;

                // Mostrar un mensaje de confirmación
                const confirmationMessage = document.createElement('p');
                confirmationMessage.textContent = '¡Gracias por tu respuesta!';
                confirmationMessage.className = 'text-green-600 mt-2';
                document.querySelector('.survey-container').appendChild(confirmationMessage);
            }

        </script>
    @endpush
</x-app-layout>
