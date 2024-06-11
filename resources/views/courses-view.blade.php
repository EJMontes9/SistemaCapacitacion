@vite(['resources/js/courses-view.js'])
<x-app-layout>
    <div>
        <x-course.hero-view :course="$course"  :name="$name_user"/>
    </div>
    <div>
        <x-course.list-section-view :section="$section" :lesson="$lesson" :course="$course" :evaluation="$evaluation"/>
    </div>

    <section class="bg-gray-100 py-8">
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
    </section>

</x-app-layout>
