<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Crea un nuevo curso</h1>
        <x-validation-errors />
        <form action="{{route('courses.store')}}" method="post">
            @csrf
            <div class="flex flex-col justify-center">
                <div class="flex flex-col w-full lg:flex-row">
                    <x-input class="mt-4 lg:mr-2 lg:w-1/2" type="text" name="title" placeholder="Titulo del curso" />
                    <x-input class="mt-4 lg:w-1/2" type="text" name="subtitle" placeholder="Subtitulo del curso" />
                </div>
                <x-text-area class="mt-4" type="text" name="description" placeholder="Descripción del curso" />
                <x-input class="mt-4" type="text" name="slug" placeholder="slug" />
                <div class="flex flex-col lg:flex-row">
                    <x-combobox class="mt-4 lg:w-1/2 lg:mr-2" name="category">
                        <x-slot name="title">Categoria</x-slot>
                        <option class="text-gray-300" value="0">Seleccione una categoria...</option>
                        @foreach ($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </x-combobox>
                    <x-combobox class="mt-4 lg:w-1/2" name="level">
                        <x-slot name="title">Nivel</x-slot>
                        <option class="text-gray-300" value="0">Seleccione un nivel...</option>
                        @foreach ($levels as $level)
                            <option value="{{$level->id}}">{{$level->name}}</option>
                        @endforeach
                    </x-combobox>
                </div>
                <x-input class="mt-4" type="text" name="price" placeholder="Precio del curso" />
                <x-input class="mt-4" type="text" name="image" placeholder="Link de la imagen" />
            </div>
            <x-button class="mt-4" type="submit">Save</x-button>
        </form>
    </div>
</x-app-layout>
