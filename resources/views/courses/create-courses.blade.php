<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Crea un nuevo curso</h1>
        <x-validation-errors />
        <div class="mt-4 mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
            Las tareas del curso se administran desde la edición del curso una vez que lo guardes.
        </div>
        <form action="{{ route('courses.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <x-course.form-courses :categories="$categories" :levels="$levels" :modalidades="$modalidades" :periodos="$periodos" :sedes="$sedes" :niveles="$niveles" />
            <x-button class="mt-4" type="submit">Guardar</x-button>
        </form>
    </div>
</x-app-layout>