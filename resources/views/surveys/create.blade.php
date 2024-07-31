<meta name="csrf-token" content="{{ csrf_token() }}">
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Crear Nueva Encuesta</h2>
                <form id="survey-form">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Título:</label>
                        <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                        <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Categoría:</label>
                        <select name="category" id="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="lesson">Lección</option>
                            <option value="course">Curso</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="target_id" class="block text-gray-700 text-sm font-bold mb-2">ID del objetivo:</label>
                        <input type="number" name="target_id" id="target_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tipos de preguntas:</label>
                        <div>
                            <input type="checkbox" name="has_yes_no" id="has_yes_no" value="1">
                            <label for="has_yes_no">Sí/No</label>
                        </div>
                        <div>
                            <input type="checkbox" name="has_rating" id="has_rating" value="1">
                            <label for="has_rating">Calificación (1-5)</label>
                        </div>
                        <div>
                            <input type="checkbox" name="has_comment" id="has_comment" value="1">
                            <label for="has_comment">Comentario</label>
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Crear Encuesta</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('survey-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Convierte los checkboxes a booleanos
    data.has_yes_no = !!data.has_yes_no;
    data.has_rating = !!data.has_rating;
    data.has_comment = !!data.has_comment;

    // Mapea la categoría seleccionada al tipo de modelo correspondiente
    const targetTypeMap = {
        'lesson': 'App\\Models\\Lesson',
        'course': 'App\\Models\\Course',
        'section': 'App\\Models\\Section'
    };
    data.target_type = targetTypeMap[data.category];

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
        window.location.href = '/surveys';
    })
    .catch(error => {
        console.error('Error:', error);
        alert(`Error al crear la encuesta: ${error.message}`);
    });
});
    </script>
</x-app-layout>