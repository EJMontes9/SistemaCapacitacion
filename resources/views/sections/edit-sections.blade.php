<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Editar una seccion</h1>
        <x-validation-errors />
        <form action="{{route('sections.update',$section->id)}}" method="post">
            @method('PUT')
            @csrf
            <x-sections.form-sections :course="$courses" :sections="$section" />
            <x-button class="mt-4" type="submit">Guardar</x-button>
        </form>
    </div>
</x-app-layout>

