<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Crea un nuevo curso</h1>
        <x-validation-errors />
        <form action="{{route('courses.store')}}" method="post">
            @csrf
            <div class="flex flex-col justify-center w-1/3 ">
                <x-input class="mt-4" type="text" name="title" placeholder="Titulo del curso" />
                <x-input class="mt-4" type="text" name="subtitle" placeholder="Subtitulo del curso" />
                <x-input class="mt-4" type="text" name="descriptior" placeholder="Descripción del curso" />
                <x-input class="mt-4" type="text" name="slug" placeholder="slug" />
                <x-input class="mt-4" type="text" name="price" placeholder="Precio del curso" />
            </div>
            <x-button type="submit">Save</x-button>
        </form>
    </div>
</x-app-layout>
