<x-app-layout>
    <div class="mt-10 ml-10">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Has culminado tu evaluación del curso ') }} {{ $course->title }} del módulo {{ $section->name }}
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
                            {{ $user->name }} tu calificación de la evaluación del último intento es
                            <span
                                class="{{ $totalScore > 7 ? 'text-green-600' : ($totalScore == 7 ? 'text-yellow-500' : 'text-red-500') }}">
                                {{ $totalScore }}/{{ $totalEvaluationScore }}
                            </span>
                        </p>
                    </div>

                    <!-- Aquí se mostrará el historial de intentos -->

                    @php
                        \Carbon\Carbon::setLocale('es');
                    @endphp
                    <div class="card mt-10">
                        <p class="text-justify text-lg font-bold">Historial de intentos</p>
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
                                        <td class="border px-4 py-2 text-center">{{ $result->total_score }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

                                        {{-- Muestra las opciones de la pregunta --}}
                                        @foreach ($incorrectQuestion['question']->options as $option)
                                            @if ($option->correct_answer)
                                                {{-- Solo muestra las opciones correctas --}}
                                                <p>
                                                    Opción: {{ $option->options }}
                                                    <span class="text-green-500">Respuesta correcta</span>
                                                </p>
                                            @endif
                                        @endforeach

                                        <p class="font-semibold">Tu selección</p>

                                        {{-- Muestra las opciones seleccionadas incorrectas --}}
                                        @foreach ($incorrectQuestion['selectedOptions'] as $selectedOption)
                                            @if (!$selectedOption->correct_answer)
                                                <p>
                                                    Opción: {{ $selectedOption->options }}
                                                    <span class="text-red-500">Respuesta seleccionada
                                                        incorrecta</span>
                                                </p>
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
                        <div
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-40 text-center">
                            <a href="{{ route('evaluations.show', ['evaluation' => $evaluation->id]) }}">Volver a
                                Intentar</a>
                        </div>
                        <div
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-32 text-center">
                            <a href="/listcourse">Regresar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('incorrectQuestionsHeader').addEventListener('click', function() {
            var body = document.getElementById('incorrectQuestionsBody');
            if (body.style.display === "none") {
                body.style.display = "block";
            } else {
                body.style.display = "none";
            }
        });

        function toggleColor(element) {
            element.classList.toggle('bg-red-300');
            element.classList.toggle('text-white');
        }
    </script>

</x-app-layout>
