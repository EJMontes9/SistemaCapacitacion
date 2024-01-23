<div class="flex flex-col justify-center">
    <div class="flex flex-col w-full lg:flex-row">
        <x-input class="mt-4 lg:mr-2 lg:w-1/2" type="text" name="name" placeholder="Titulo de la seccion" value="{{ old('title', $sections->title ?? '') }}" />
        <x-combobox class="mt-4 lg:w-1/2 lg:mr-2 text-gray-500" name="course_id">
            <x-slot name="title">Curso</x-slot>
            <option value="0">Seleccione un curso...</option>
            @foreach ($course as $id => $title)
                <option value="{{$id}}" {{ old('course_id', $sections->course_id ?? '') == $id ? 'selected' : '' }}>{{$title}}</option>
            @endforeach
        </x-combobox>
    </div>
</div>
