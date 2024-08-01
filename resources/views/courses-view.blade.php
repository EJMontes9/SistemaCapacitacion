@vite(['resources/js/courses-view.js'])
<x-app-layout>
    <div>
        <x-course.hero-view :course="$course" :name="$name_user"/>
    </div>

    <div class="w-full">
        <!-- botonera de los tabs -->
        <div class="flex justify-center mb-4">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <!-- Tab 1 - Contenido -->
                    <a href="#" role="tab" class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none text-indigo-600 hover:text-indigo-800" aria-current="page">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Contenido del curso</span>
                    </a>
                    <!-- Tab 2 Estadísticas Generales -->
                    <a href="#" role="tab" class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Estadísticas Generales</span>
                    </a>
                    <!-- Tab 3 Estadísticas para el Instructor -->
                    @hasanyrole('Instructor|Admin')
                    <a href="#" role="tab" class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Estadísticas Para el Instructor</span>
                    </a>
                    @endhasanyrole
                    <a href="#" role="tab" class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Valoraciones</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Contenido de los tabs -->
        <div class="mt-6">
            <!-- Tab 1 Content -->
            <div role="tabpanel" class="p-4 bg-white rounded-lg shadow">
                <div>
                    <x-course.list-section-new-view  :sections="$sections"  :lessons="$lessons"  :resources="$resources"  :course="$course"  :evaluation="$evaluation"/>
                </div>
            </div>
            <!-- Tab 2 Content -->
            <div role="tabpanel" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                <x-course.coursestats-view />
            </div>
            <!-- Tab 3 Content -->
            @hasanyrole('Instructor|Admin')
            <div role="tabpanel" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                <x-course.coursegeneralstats-view />
            </div>
            @endhasanyrole

            <!-- Tab 4 Content -->
            <div role="tabpanel" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                <x-course.valoracion-view :courseId="$course->id" />
            </div>
        </div>
    </div>

{{-- // script que hacen funcionar los tabs --}}
<script>
    // script que hacen funcionar los tabs
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('a[role="tab"]');
        const tabContents = document.querySelectorAll('div[role="tabpanel"]');
        function hideAllTabContents() {// Función para ocultar todos los contenidos de los tabs
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            tabs.forEach(content => {
                content.classList.remove('text-indigo-600');
            });
        }
        function showActiveTabContent(index) {// Función para mostrar el contenido del tab activo
            hideAllTabContents();
            tabContents[index].classList.remove('hidden');
            tabs[index].classList.add('text-indigo-600');
        }
        tabs.forEach((tab, index) => {// Agregar eventos de clic a los enlaces de los tabs
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                showActiveTabContent(index);
            });
        });
        showActiveTabContent(0);// Mostrar el contenido del primer tab por defecto
    });
</script>


</x-app-layout>

