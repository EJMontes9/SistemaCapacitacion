<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Crea una nueva seccion</h1>
        <x-validation-errors />
        <form action="{{route('sections.store')}}" method="post">
            @csrf
            <x-sections.form-sections :course="$courses" />
            <x-button class="mt-4" type="submit">Guardar</x-button>
        </form>
    </div>
</x-app-layout>

