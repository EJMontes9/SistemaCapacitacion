<x-app-layout>
    <nav class="bg-white border-b border-gray-200 fixed z-30 w-full">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar"
                        class="lg:hidden mr-2 text-gray-600 hover:text-gray-900 cursor-pointer p-2 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 rounded">
                        <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <svg id="toggleSidebarMobileClose" class="w-6 h-6 hidden" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <a href="#" class="text-xl font-bold flex items-center lg:ml-2.5 w-1/3 h-1/3">
                        <x-application-mark class="block h-9 w-auto" />
                        <span class="self-center whitespace-nowrap ml-3">StudyApp</span>
                    </a>
                    <form action="#" method="GET" class="hidden lg:block lg:pl-32">
                        <label for="topbar-search" class="sr-only">Search</label>
                        <div class="mt-1 relative lg:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" name="email" id="topbar-search"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full pl-10 p-2.5"
                                placeholder="Search">
                        </div>
                    </form>
                </div>
                <div class="flex items-center">
                    <button id="toggleSidebarMobileSearch" type="button"
                        class="lg:hidden text-gray-500 hover:text-gray-900 hover:bg-gray-100 p-2 rounded-lg">
                        <span class="sr-only">Search</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div>
        <div class="flex overflow-hidden bg-white pt-16">
            <div class="bg-gray-900 opacity-50 hidden fixed inset-0 z-10" id="sidebarBackdrop"></div>
            <div id="main-content"
                class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64 flex flex-col min-h-screen">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div>
                                @if (session('success'))
                                    <div id="success-message"
                                        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                                        role="alert">
                                        <strong class="font-bold">{{ session('success') }}</strong>
                                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 closealertbutton">
                                            <svg class="fill-current h-6 w-6 text-green-500" role="button"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <title>Close</title>
                                                <path
                                                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z">
                                                </path>
                                            </svg>
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="card">
                                <div class="card-header text-2xl font-bold mb-3 mt-3">
                                    Laravel - Mis Evaluaciones
                                </div>
                            </div>
                            <div class="p-6 bg-white border-b border-gray-200">
                                <a href="{{ route('evaluations.create') }}"
                                    class="mb-4 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Crear nueva evaluación
                                </a>

                                <!-- Tabla de evaluaciones -->

                                <div class="relative overflow-x-auto shadow-lg sm:rounded-lg">
                                    @if ($evaluations->isEmpty())
                                        <div class="text-center text-gray-500 text-2xl font-semibold mb-2">
                                            <i class="fa-solid fa-graduation-cap text-6xl mb-2"></i>
                                            <h2>Aun no hay registros</h2>
                                        </div>
                                    @else
                                        <table
                                            class="table-auto w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 shadow-md rounded-lg">
                                            <thead
                                                class="font-bold text-lg text-center text-black bg-gray-200 dark:bg-gray-300 rounded-md">
                                                <tr>
                                                    <th class="px-4 py-2 w-1/5">Título</th>
                                                    <th class="px-4 py-2">Descripción</th>
                                                    <th class ="px-4 py-2 w-1/5">Fecha de creación</th>
                                                    <th class ="px-4 py-2 w-1/5">Fecha de actualización</th>
                                                    <th class="px-4 py-2 w-1/5">Acciones</th>
                                                </tr>
                                            </thead>
                                            <!-- Aquí va el código para mostrar las preguntas y las opciones -->
                                            <tbody>
                                                @foreach ($evaluations as $index => $evaluation)
                                                    <tr
                                                        class="{{ $index % 2 == 0 ? 'bg-gray-100 text-black' : 'bg-white text-black' }}">
                                                        <td class="border px-4 py-2 w-1/5">{{ $evaluation->title }}</td>
                                                        <td class="border px-4 py-2 text-justify">
                                                            {{ $evaluation->description }}</td>
                                                        <td class="border px-4 py-2 text-center w-1/5">
                                                            {{ \Carbon\Carbon::parse($evaluation->created_at)->locale('es')->isoFormat('LL') }}
                                                        </td>
                                                        <td class="border px-4 py-2 text-center w-1/5">
                                                            {{ \Carbon\Carbon::parse($evaluation->updated_at)->locale('es')->isoFormat('LL') }}
                                                        </td>
                                                        <td class="border px-4 py-2 text-center w-1/5">
                                                            <a href="{{ route('evaluations.show', $evaluation) }}"
                                                                class="text-blue-400 hover:text-blue-600">
                                                                <i class="fas fa-eye"></i>
                                                            </a> |
                                                            <a href="{{ route('evaluations.edit', $evaluation) }}"
                                                                class="text-green-400 hover:text-green-600">
                                                                <i class="fas fa-edit"></i>
                                                            </a> |
                                                            <form method="POST"
                                                                action="{{ route('evaluations.destroy', $evaluation) }}"
                                                                style="display: inline;"
                                                                onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta evaluación?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-400 hover:text-red-600">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>

                                <!-- Fin de la tabla de evaluaciones -->

                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-auto">
                    <x-footer></x-footer>
                </div>
            </div>
        </div>
    </div>

    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://demo.themesberg.com/windster/app.bundle.js"></script>
    @push('scripts')
        <script src="{{ mix('resources\js\alert.js') }}"></script>
    @endpush

</x-app-layout>
