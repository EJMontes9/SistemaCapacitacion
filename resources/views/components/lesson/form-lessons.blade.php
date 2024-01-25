<div class="flex flex-col justify-center">
    <div class="flex flex-col w-full lg:flex-row">
        <x-input class="mt-4 lg:mr-2 lg:w-1/2" type="text" name="name" placeholder="Nombre de la leccion" value="{{ old('title', $lessons->name ?? '') }}" />
        <x-combobox class="mt-4 lg:w-full lg:mr-2 text-gray-500" name="section_id">
            <x-slot name="title">Seccion</x-slot>
            <option value="0">Seleccione una seccion...</option>
            @foreach ($section as $id => $name)
                <option value="{{$id}}" {{ old('section_id', $lessons->section_id ?? '') == $id ? 'selected' : '' }}>{{$name}}</option>
            @endforeach
        </x-combobox>
    </div>
    <x-combobox class="mt-4 lg:w-full lg:mr-2 text-gray-500" name="platform_id">
        <x-slot name="title">Plataforma</x-slot>
        <option value="0">Seleccione una plataforma...</option>
        @foreach ($platform as $id => $name)
            <option value="{{$id}}" {{ old('platform_id', $lessons->platform_id ?? '') == $id ? 'selected' : '' }}>{{$name}}</option>
        @endforeach
    </x-combobox>
    <x-input class="mt-4 lg:mr-2 lg:w-full" type="text" name="url" placeholder="Url de la leccion" value="{{ old('title', $lessons->url ?? '') }}" />
    <x-text-area class="mt-4" type="text" name="iframe" placeholder="iframe del video">{{ old('iframe', $lessons->iframe ?? '') }}</x-text-area>
</div>
