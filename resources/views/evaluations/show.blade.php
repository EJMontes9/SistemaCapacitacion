<x-app-layout>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="container mx-auto px-4">
                <a href="#" onclick="goBack()"
                    class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 mb-7">
                    Regresar
                </a>
                <h1 class="text-4xl font-bold my-4">{{ $evaluation->title }}</h1>
                <p class="text-lg mb-8 mt-7">{{ $evaluation->description }}</p>

                <form method="POST" action="{{ route('evaluations.finished', $evaluation->id) }}">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="module_id" value="{{ $section->id }}">

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Itera sobre las preguntas -->
                        @foreach ($questions as $question)
                            <div class="card bg-white shadow-lg rounded-lg p-6">
                                <div class="flex justify-between">
                                    <h2 class="text-lg font-semibold mb-2">{{ $loop->iteration }}.
                                        {{ $question->question }}
                                    </h2>
                                    <h2 class="text-lg font-semibold mb-2"> Puntuación: {{ $question->score }}</h2>
                                </div>
                                <!-- Aquí es donde se generan las opciones de respuesta -->
                                <div class="flex flex-col gap-2">
                                    @php
                                        $options = json_decode($question->options, true);
                                        $correctOptionsCount = collect($options)
                                            ->where('correct_answer', true)
                                            ->count();
                                    @endphp

                                    @foreach ($options as $option)
                                        <label class="inline-flex items-center">
                                            @if ($correctOptionsCount > 1)
                                                <!-- Checkbox para múltiples respuestas correctas -->
                                                <input type="checkbox"
                                                    class="form-radio text-blue-600 h-5 w-5 rounded-sm"
                                                    name="questions[{{ $question->id }}][]"
                                                    value="{{ $option['id'] }}">
                                            @else
                                                <!-- Radiobutton para una única respuesta correcta -->
                                                <input type="radio"
                                                    class="form-radio text-blue-600 h-5 w-5 rounded-full"
                                                    name="questions[{{ $question->id }}][]"
                                                    value="{{ $option['id'] }}">
                                            @endif
                                            <span class="ml-2">{{ $option['options'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (Auth::user()->hasRole('Alumno'))
                        <div class="flex justify-between items-center mt-8">
                            <button type="submit" id="finalizarEvaluacion" onclick="return confirmSubmit();"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Finalizar Evaluación
                            </button>

                            <a href="{{ url()->previous() }}" onclick="return confirmCancel();"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ mix('resources/js/createevaluation.js') }}"></script>
        <script>
            function confirmSubmit() {
                return confirm('¿Estás seguro de que quieres enviar tus respuestas?');
            }

            function confirmCancel() {
                return confirm('¿Estás seguro de que quieres salir?');
            }

            function goBack() {
                window.history.back();
            }
        </script>
    @endpush

</x-app-layout>
