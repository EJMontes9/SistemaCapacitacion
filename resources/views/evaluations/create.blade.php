<x-app-layout>

    <div id="main-content" class="bg-gray-50 mx-5 my-4">

        @if ($errors->any())
            <div class="bg-red-500 text-white px-4 py-2 rounded mb-2" id="error-alert">
                <strong>Error!</strong>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="card">
            <h1 class="font-bold text-2xl mb-2">Formulario de Evaluaciones</h1>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('evaluations.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="text-xl text-gray-600">Título de la evaluación <span
                                class="text-red-500">*</span></label></br>
                        <input type="text" class="border-2 border-gray-300 p-2 w-full rounded-lg" name="title"
                            id="title" value="" placeholder="Ingresa un título para tu evaluación" required>
                    </div>

                    <div class="mb-2">
                        <label class="text-xl text-gray-600">Descripción <span
                                class="text-red-500">*</span></label></br>
                        <textarea name="description" class="border-2 border-gray-300 p-2 w-full rounded-lg" id="description"
                            placeholder="Ingresa una breve descripción aquí" required></textarea>
                    </div>

                    <div class="flex space-x-4">
                        <div class="mb-8 w-1/2">
                            <label class="text-lg text-gray-600" for="course_id">
                                Curso
                            </label> <span class="text-red-500">*</span></label></br>
                            <select name="course_id" id="course_id"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-600 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="">Selecciona una opción</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-8 w-1/2">
                            <label class=" text-lg text-gray-600" for="module_id">
                                Secciones
                            </label> <span class="text-red-500">*</span></label></br>
                            <select name="module_id" id="module_id"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-600 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="">Selecciona una opción</option>
                                @foreach ($courses as $course)
                                    @foreach ($course->sections as $section)
                                        <option value="{{ $section->id }}" data-course-id="{{ $course->id }}">
                                            {{ $section->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="questions-container" class="shadow-md rounded-md p-5 mb-2">
                        <!-- Las tarjetas de las preguntas se agregarán aquí -->
                    </div>

                    <button type="button" id="add-question-button"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar
                        pregunta</button>
                    <button type="button" id="remove-question-button"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded" hidden>Eliminar
                        pregunta</button>

                    <div class="flex justify-end p-1">
                        <button role="submit"
                            class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mt-4"
                            required>Guardar</button>
                        <a href="{{ route('evaluations.index') }}"
                            onclick="event.preventDefault(); if(confirm('¿Estás seguro de que quieres cancelar?')) { window.location.href = this.href; }"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4 ml-2 no-underline">Cancelar</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="{{ mix('resources\js\createevaluation.js') }}"></script>
    @endpush

    @push('scripts')
        <script src="{{ mix('resources\js\alert.js') }}"></script>
    @endpush

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <!-- Script para filtrar los módulos por curso -->
    <script>
        $(document).ready(function() {
            $('#course_id').change(function() {
                var courseId = $(this).val();
                $('#module_id').val(
                    ''); // Cambiar el valor del menú desplegable de módulos al valor por defecto
                $('#module_id option').each(function() {
                    var optionCourseId = $(this).data('course-id');
                    if (courseId == optionCourseId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

</x-app-layout>
