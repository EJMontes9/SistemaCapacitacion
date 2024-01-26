<x-app-layout>

    <div id="main-content" class="bg-gray-50 relative overflow-y-auto flex flex-col">
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

                @if (session('error'))
                    <div id="error-alert"
                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">{{ session('error') }}</strong>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 closealertbutton">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button"
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
                <div class="card-header text-2xl font-bold my-3 mx-5">
                    Laravel - Mis Evaluaciones
                </div>
            </div>
            <div class="p-6 bg-white border-b border-gray-200 mx-5">
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
                                                <button type="submit" class="text-red-400 hover:text-red-600">
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
            <div class="mt-8 mx-5 px-5 mb-5">
                {{ $evaluations->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ mix('resources\js\alert.js') }}"></script>
    @endpush

</x-app-layout>
