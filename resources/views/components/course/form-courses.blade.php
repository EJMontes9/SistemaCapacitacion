@csrf
<div class="flex flex-col justify-center">
    <div class="flex flex-col w-full lg:flex-row">
        <x-input class="mt-4 lg:mr-2 lg:w-1/2" type="text" name="title" placeholder="Titulo del curso" value="{{ old('title', $course->title ?? '') }}" />
        <x-input class="mt-4 lg:w-1/2" type="text" name="subtitle" placeholder="Subtitulo del curso" value="{{ old('subtitle', $course->subtitle ?? '') }}" />
    </div>
    <x-text-area class="mt-4" type="text" name="description" placeholder="Descripción del curso">{{ old('description', $course->description ?? '') }}</x-text-area>
    <div class="flex flex-col lg:flex-row">
        <x-combobox class="mt-4 lg:w-1/2 lg:mr-2 text-gray-500" name="category_id">
            <x-slot name="title">Categoria</x-slot>
            <option value="0">Seleccione una categoria...</option>
            @foreach ($categories as $id => $name)
                <option value="{{$id}}" {{ old('category_id', $course->category_id ?? '') == $id ? 'selected' : '' }}>{{$name}}</option>
            @endforeach
        </x-combobox>
        <x-combobox class="mt-4 lg:w-1/2 text-gray-500" name="level_id">
            <x-slot name="title">Nivel</x-slot>
            <option  value="0">Seleccione un nivel...</option>
            @foreach ($levels as $id => $name)
                <option value="{{$id}}" {{ old('level_id', $course->level_id ?? '') == $id ? 'selected' : '' }}>{{$name}}</option>
            @endforeach
        </x-combobox>
    </div>
    <x-upload-file name="image"></x-upload-file>
</div>
