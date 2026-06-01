<x-app-layout>
    <div id="main-content" class="bg-gray-50 mx-5 my-4">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">{{ session('success') }}</strong>
            </div>
        @endif

        <div class="card">
            <h1 class="font-bold text-2xl mb-2">Libro de Calificaciones</h1>
            <p class="text-gray-600 mb-4">Selecciona un curso para ver sus calificaciones</p>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            @if($courses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($courses as $course)
                        <a href="{{ route('evaluations.gradebook', $course->id) }}"
                           class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-lg hover:border-blue-300 transition-all duration-200">
                            <h5 class="text-lg font-bold text-gray-900 mb-2">{{ $course->title }}</h5>
                            <p class="text-sm text-gray-600">
                                {{ $course->users_count ?? $course->students->count() ?? 0 }} estudiantes
                            </p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-4 text-gray-500 text-lg">No tienes cursos asignados</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>