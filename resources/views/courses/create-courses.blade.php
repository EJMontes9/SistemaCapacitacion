<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Crea un nuevo curso</h1>
        <x-validation-errors />
        <form action="{{ route('courses.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-course.form-courses :categories="$categories" :levels="$levels" />

            <!-- Secciones nuevas -->
            <div class="mt-4">
                <h3 class="text-lg font-bold mb-2">Nuevas Secciones</h3>
                <div x-data="{ sections: [] }">
                    <template x-for="(section, index) in sections" :key="index">
                        <div class="flex items-center mt-2">
                            <x-input class="w-full mr-2" type="text" name="sections[]" x-model="sections[index]" placeholder="Nombre de la sección" />
                            <button type="button" class="text-red-500" @click="sections.splice(index, 1)">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" class="mt-2 text-blue-500" @click="sections.push('')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Agregar Sección
                    </button>
                </div>
            </div>

            <x-button class="mt-4" type="submit">Guardar</x-button>
        </form>
    </div>
</x-app-layout>