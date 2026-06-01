<x-app-layout>
    <div id="main-content" class="bg-gray-50 mx-5 my-4">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">{{ session('success') }}</strong>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-white px-4 py-2 rounded mb-2">
                <strong>Error!</strong>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="card">
            <h1 class="font-bold text-2xl mb-2">Banco de Preguntas - {{ $course->title }}</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h2 class="font-bold text-xl mb-4">Agregar Pregunta</h2>
                    <form method="POST" action="{{ route('evaluations.question-bank.store', $course->id) }}">
                        @csrf

                        <div class="mb-4">
                            <label class="text-lg text-gray-600">Pregunta <span class="text-red-500">*</span></label>
                            <textarea name="question" rows="3" class="border-2 border-gray-300 p-2 w-full rounded-lg" required>{{ old('question') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="text-lg text-gray-600">Tipo <span class="text-red-500">*</span></label>
                            <select name="type" id="question-type" class="border-2 border-gray-300 p-2 w-full rounded-lg" required>
                                <option value="multiple_choice">Opción Múltiple</option>
                                <option value="true_false">Verdadero/Falso</option>
                                <option value="short_answer">Respuesta Corta</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="text-lg text-gray-600">Puntaje <span class="text-red-500">*</span></label>
                            <input type="number" name="score" step="0.01" min="0" value="{{ old('score', 1) }}"
                                class="border-2 border-gray-300 p-2 w-full rounded-lg" required>
                        </div>

                        <div id="multiple-choice-options" class="mb-4">
                            <label class="text-lg text-gray-600">Opciones <span class="text-red-500">*</span></label>
                            <div id="options-container">
                                <div class="flex items-center mb-2">
                                    <input type="text" name="options[]" placeholder="Opción 1"
                                        class="border-2 border-gray-300 p-2 w-full rounded-lg">
                                    <input type="checkbox" name="correct_option[]" value="1" class="ml-2">
                                    <label class="ml-1 text-sm">Correcta</label>
                                </div>
                                <div class="flex items-center mb-2">
                                    <input type="text" name="options[]" placeholder="Opción 2"
                                        class="border-2 border-gray-300 p-2 w-full rounded-lg">
                                    <input type="checkbox" name="correct_option[]" value="1" class="ml-2">
                                    <label class="ml-1 text-sm">Correcta</label>
                                </div>
                            </div>
                            <button type="button" id="add-option"
                                class="text-blue-500 hover:text-blue-700 text-sm mt-2">+ Agregar opción</button>
                        </div>

                        <div id="true-false-options" class="mb-4" style="display: none;">
                            <label class="text-lg text-gray-600">Respuesta correcta <span class="text-red-500">*</span></label>
                            <select name="true_false_correct" class="border-2 border-gray-300 p-2 w-full rounded-lg">
                                <option value="true">Verdadero</option>
                                <option value="false">Falso</option>
                            </select>
                        </div>

                        <div id="short-answer-note" class="mb-4" style="display: none;">
                            <p class="text-gray-500 text-sm">Las preguntas de respuesta corta se calificarán manualmente.</p>
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar Pregunta
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h2 class="font-bold text-xl mb-4">Preguntas del Banco ({{ $questions->count() }})</h2>

                    @if ($questions->isEmpty())
                        <div class="text-center text-gray-500 text-lg font-semibold py-10">
                            <p>No hay preguntas en el banco.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($questions as $question)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">{{ $question->question }}</p>
                                            <div class="flex space-x-3 mt-2 text-sm text-gray-500">
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                    {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                </span>
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded">
                                                    Puntaje: {{ $question->score }}
                                                </span>
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                    Creado por: {{ $question->creator->name ?? 'Desconocido' }}
                                                </span>
                                            </div>
                                            @if ($question->options->count() > 0)
                                                <div class="mt-2 text-sm text-gray-600">
                                                    @foreach ($question->options as $option)
                                                        <span class="mr-3 {{ $option->correct_answer ? 'text-green-600 font-semibold' : '' }}">
                                                            {{ $option->options }} {{ $option->correct_answer ? '(✓)' : '' }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const typeSelect = document.getElementById('question-type');
                const mcOptions = document.getElementById('multiple-choice-options');
                const tfOptions = document.getElementById('true-false-options');
                const saNote = document.getElementById('short-answer-note');
                const addOptionBtn = document.getElementById('add-option');
                const optionsContainer = document.getElementById('options-container');
                let optionCount = 2;

                function toggleFields() {
                    const type = typeSelect.value;
                    mcOptions.style.display = type === 'multiple_choice' ? 'block' : 'none';
                    tfOptions.style.display = type === 'true_false' ? 'block' : 'none';
                    saNote.style.display = type === 'short_answer' ? 'block' : 'none';

                    document.querySelectorAll('#multiple-choice-options input').forEach(input => {
                        input.required = type === 'multiple_choice';
                    });
                    document.querySelectorAll('#true-false-options select').forEach(input => {
                        input.required = type === 'true_false';
                    });
                }

                typeSelect.addEventListener('change', toggleFields);
                toggleFields();

                addOptionBtn.addEventListener('click', function() {
                    optionCount++;
                    const div = document.createElement('div');
                    div.className = 'flex items-center mb-2';
                    div.innerHTML = `
                        <input type="text" name="options[]" placeholder="Opción ${optionCount}"
                            class="border-2 border-gray-300 p-2 w-full rounded-lg">
                        <input type="checkbox" name="correct_option[]" value="1" class="ml-2">
                        <label class="ml-1 text-sm">Correcta</label>
                        <button type="button" class="remove-option ml-2 text-red-500 hover:text-red-700">X</button>
                    `;
                    optionsContainer.appendChild(div);
                });

                optionsContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-option')) {
                        if (optionsContainer.children.length > 2) {
                            e.target.parentElement.remove();
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
