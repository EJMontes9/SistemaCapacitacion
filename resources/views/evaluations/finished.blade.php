<x-app-layout>
    @if ($rolId == 3)

        <div class="mt-10 ml-10">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                @if (Route::is('evaluations.view'))
                    {{ __('Mis calificaciones del curso ') }} {{ $course->title }} {{ __(' de la sección ') }}
                    {{ $section->name }}
                @else
                    {{ __('Has culminado tu evaluación del curso ') }} {{ $course->title }} {{ __(' de la sección ') }}
                    {{ $section->name }}
                @endif
            </h2>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="card mb-4">
                            <h3 class="text-2xl text-center font-semibold">{{ $evaluation->title }}</h3>
                        </div>
                        <div class="card mb-4">
                            <p class="text-center text-lg mt-10">{{ $evaluation->description }}</p>
                        </div>
                        <div class="card mt-11">
                            <p class="text-center text-xl font-bold">
                                @if (isset($totalScore))
                                    {{ $user->name }} tu calificación de la evaluación del último intento es
                                    <span
                                        class="{{ $totalScore > 7 ? 'text-green-600' : ($totalScore == 7 ? 'text-yellow-500' : 'text-red-500') }}">
                                        {{ $totalScore }}/{{ $totalEvaluationScore }}
                                    </span>
                                @else
                                    {{ $user->name }}, aquí se muestran tus calificaciones de esta evaluación.
                                @endif
                            </p>
                        </div>
                        <!-- Aquí se mostrará el historial de intentos -->
                        @php \Carbon\Carbon::setLocale('es'); @endphp
                        <div class="card mt-10">
                            <p class="text-justify text-lg font-bold">Historial de intentos</p>
                            @if ($evaluationResults->isEmpty())
                                <div class="card bg-orange-300 rounded-lg my-5 mx-5 py-4 px-4 shadow-xl">
                                    <p>Sin resultados, realiza tu primer intento en esta evaluación para poder
                                        visualizar tus resultados aquí</p>
                                </div>
                            @else
                                <table class="table-auto w-full mt-3 rounded-lg">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 text-center">Fecha de finalización de evaluación</th>
                                            <th class="px-4 py-2 text-center">Calificación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($evaluationResults as $index => $result)
                                            <tr class="{{ $index % 2 == 0 ? 'bg-gray-100' : '' }}">
                                                <td class="border px-4 py-2 text-center">
                                                    {{ $result->created_at->isoFormat('dddd D [de] MMMM [del] YYYY [a las] HH:mm:ss') }}
                                                    @if ($index == 0)
                                                        <span class="text-red-500">(Último intento realizado)</span>
                                                    @endif
                                                </td>
                                                <td class="border px-4 py-2 text-center">{{ $result->total_score }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        <!-- Aquí es donde se agregan las preguntas incorrectas -->
                        @if (!empty($incorrectQuestions))
                            <div class="card shadow-xl my-8">
                                <div class="text-red-500 card-header text-lg font-semibold py-4 pl-5 rounded-lg hover:bg-red-300 hover:text-white"
                                    id="incorrectQuestionsHeader" style="cursor: pointer;" onclick="toggleColor(this)">
                                    Ver mis respuestas incorrectas del último intento realizado
                                </div>
                                <div class="card-body pl-5 pb-4" id="incorrectQuestionsBody" style="display: none;">
                                    @foreach ($incorrectQuestions as $incorrectQuestion)
                                        <div class="card">
                                            <h2 class="font-semibold mt-4">Pregunta:
                                                {{ $incorrectQuestion['question']->question }}</h2>
                                            <p class="font-semibold text-red-500">Tu selección</p>
                                            {{-- Muestra las opciones seleccionadas incorrectas --}}
                                            @foreach ($incorrectQuestion['selectedOptions'] as $selectedOption)
                                                @if (!$selectedOption->correct_answer)
                                                    <p> Opción: {{ $selectedOption->options }} <span
                                                            class="text-red-500"> - La respuesta seleccionada es
                                                            incorrecta</span> </p>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                    {{-- Muestra las preguntas no respondidas --}}
                                    @foreach ($unansweredQuestions as $unansweredQuestion)
                                        <div class="card">
                                            <h2 class="font-semibold mt-4">Pregunta:
                                                {{ $unansweredQuestion['question']->question }}</h2>
                                            <span class="text-red-500">Sin responder</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="flex justify-between items-center mt-10">
                            @if (isset($totalScore) && $totalScore < $totalEvaluationScore)
                                <div
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-45 text-center">
                                    <a href="{{ route('evaluations.show', ['evaluation' => $evaluation->id]) }}">
                                        @if ($evaluationResults->isEmpty())
                                            Realizar Evaluación
                                        @else
                                            Volver a Intentar
                                        @endif
                                    </a>
                                </div>
                            @endif
                            <div
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-32 text-center">
                                <a href="#" onclick="goBack()">Regresar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($rolId == 2 || $rolId == 1)
        <div class="mt-7 mx-8">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center">
                Reporte de Calificaciones de la Evaluación "{{ $nameEvaluation }}" de la sección "{{ $section }}"
            </h2>
        </div>

        <div>
            <div class="bg-white shadow-md rounded-lg overflow-hidden my-10 mx-5">
                <h2 class="text-center text-xl font-medium text-gray-800 my-4 mx-8">
                    Aqui se muestra el resumen de las calificaciones de los alumnos que han realizado la evaluación con
                    sus número de intentos
                </h2>
            </div>
        </div>
        @if (count($datos) === 0)
            <div class="container mx-auto">
                <div class="bg-white shadow-md rounded-lg overflow-hidden mx-auto p-5">
                    <p class="text-center text-2xl font-semibold text-gray-800 mb-10">Aún no se han realizado intentos de tus evaluaciones . . .</p>
                    <div class="flex justify-center mb-10">
                        <img src="https://www.webquestcreator2.com/majwq/files/files_user/62124/evaluacion.png"
                            alt="No hay intentos" class="w-1/4 h-1/4 opacity-30">
                    </div>
                </div>
            </div>
        @else
            <div class="container mx-auto">
                @foreach ($datos as $index => $dato)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden my-7 mx-auto">
                        <div class="text-black card-header mb-2 text-lg font-semibold py-4 pl-5 rounded-lg hover:bg-gray-300 hover:text-gray-800"
                            style="cursor: pointer;" id="header{{ $index }}">
                            {{ $dato['alumno'] }}
                            <span class="text-blue-500 ml-2">(Número de Intentos:
                                {{ count($dato['resultados']) }})</span>
                        </div>
                        <div class="card-body pl-5 pb-4" id="collapse{{ $index }}" style="display: none;">
                            @foreach ($dato['resultados'] as $resultado)
                                <p><span class="font-semibold">Nombre de la Evaluación:</span>
                                    {{ $resultado['evaluacion'] }}</p>
                                <p><span class="font-semibold">Calificación Obtenida:</span> {{ $resultado['puntuacion']}}
                                </p>
                                <p><span class="font-semibold">Fecha:</span> {{ $resultado['fecha'] }}</p>
                                <hr class="my-2">
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-32 text-center mx-auto my-10">
            <a href="#" onclick="goBack()">Regresar</a>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var header = document.getElementById('incorrectQuestionsHeader');
            if (header) {
                header.addEventListener('click', function() {
                    var body = document.getElementById('incorrectQuestionsBody');
                    if (body) {
                        if (body.style.display === "none") {
                            body.style.display = "block";
                        } else {
                            body.style.display = "none";
                        }
                    }
                });
            }
        });

        function toggleColor(element) {
            if (element) {
                element.classList.toggle('bg-red-300');
                element.classList.toggle('text-white');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var headers = document.querySelectorAll('[id^="header"]');

            headers.forEach(function(header) {
                header.addEventListener('click', function() {
                    var collapseId = 'collapse' + header.id.replace('header', '');
                    var collapseElement = document.getElementById(collapseId);

                    if (collapseElement.style.display === "none" || collapseElement.style
                        .display === "") {
                        collapseElement.style.display = "block";
                        header.classList.add('bg-gray-300', 'text-gray-800');
                    } else {
                        collapseElement.style.display = "none";
                        header.classList.remove('bg-gray-300', 'text-gray-800');
                    }
                });
            });
        });

        function goBack() {
            window.history.back();
        }
    </script>


</x-app-layout>
