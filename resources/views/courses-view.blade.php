@vite(['resources/js/courses-view.js'])
<x-app-layout>
    <div>
        <x-course.hero-view :course="$course"  :name="$name_user"/>
    </div>
    
    <div class="w-full">
        <!-- botonera de los tabs -->
        <div class="flex justify-center mb-4">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <!-- Tab 1 -->
                    <a href="#" role="tab" class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none text-indigo-600 hover:text-indigo-800" aria-current="page">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Contenido del curso</span>
                    </a>
    
                    <!-- Tab 2 -->
                    <a href="#" role="tab" class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Estadísticas de Alumnos</span>
                    </a>
                </nav>
            </div>
        </div>
    
        <!-- Contenido de los tabs -->
        <div class="mt-6">
            <!-- Tab 1 Content -->
            <div role="tabpanel" class="p-4 bg-white rounded-lg shadow">
                <div>
                    {{-- componente de secciones --}}
                    <x-course.list-section-view :section="$section" :resources="$resources" :lesson="$lesson" :course="$course" :evaluation="$evaluation"/>
                </div>
            </div>
    
            <!-- Tab 2 Content -->
            <div role="tabpanel" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                <x-course.coursestats-view />
            </div>
    
        </div>
    </div>


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

    {{-- <section class="bg-gray-100 py-8">
        <div class="max-w-3xl mx-auto px-4">

            <!-- título secciones de items -->
            <h1 class="text-2xl font-bold mb-4">Section 1: Getting Started With This Course</h1>
            <div class="space-y-4">

            <!-- item 1-->
            <div class="bg-white rounded-md shadow-sm mb-2">
                <div x-data="{ open: false }">
                <div class="p-4 cursor-pointer" @click="open = !open">
                    <h2 class="text-lg font-medium flex justify-between items-center">
                    <span>Lesson 1: What Is WordPress?</span>
                    <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    </h2>
                </div>
                <div x-show="open" x-transition>
                    <div class="p-4 border-t">
                    <p class="text-gray-700">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.
                    </p>
                    </div>
                </div>
                </div>
            </div>

            <!-- item 2-->
            <div class="bg-white rounded-md shadow-sm">
                <div x-data="{ open: false }">
                <div class="p-4 cursor-pointer" @click="open = !open">
                    <h2 class="text-lg font-medium flex justify-between items-center">
                    <span>Lesson 2: Code The Basic Webpage Layout</span>
                    <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                    </h2>
                </div>
                <div x-show="open" x-transition>
                    <div class="p-4 border-t">
                    <p class="text-gray-700">
                        Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia.
                    </p>
                    </div>
                </div>
                </div>
            </div>
            <!-- Resto de acordeones -->
            </div>
        </div>
    </section> --}}

</x-app-layout>

