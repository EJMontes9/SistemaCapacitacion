<div class="space-y-6" x-data="courseForm()" x-init="initForm()">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Título del curso <span class="text-red-500">*</span></label>
            <input type="text" name="title" x-model="title" @input="validateTitle"
                placeholder="Ej: Introducción a la Programación"
                value="{{ old('title', $course->title ?? '') }}"
                class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                :class="{'border-red-500': errors.title}">
            <template x-if="errors.title">
                <p class="text-red-500 text-xs mt-1" x-text="errors.title"></p>
            </template>
            <template x-if="!errors.title && title.length > 0">
                <p class="text-green-500 text-xs mt-1">Correcto</p>
            </template>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo <span class="text-red-500">*</span></label>
            <input type="text" name="subtitle" x-model="subtitle" @input="validateSubtitle"
                placeholder="Ej: Aprende los fundamentos desde cero"
                value="{{ old('subtitle', $course->subtitle ?? '') }}"
                class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                :class="{'border-red-500': errors.subtitle}">
            <template x-if="errors.subtitle">
                <p class="text-red-500 text-xs mt-1" x-text="errors.subtitle"></p>
            </template>
            <template x-if="!errors.subtitle && subtitle.length > 0">
                <p class="text-green-500 text-xs mt-1">Correcto</p>
            </template>
            <small class="text-gray-400">Mínimo 5 caracteres</small>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción <span class="text-red-500">*</span></label>
        <textarea name="description" x-model="description" @input="validateDescription"
            placeholder="Describe el contenido del curso, objetivos y requisitos"
            class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            :class="{'border-red-500': errors.description}">{{ old('description', $course->description ?? '') }}</textarea>
        <template x-if="errors.description">
            <p class="text-red-500 text-xs mt-1" x-text="errors.description"></p>
        </template>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
            <x-combobox name="category_id">
                <x-slot name="title">Seleccionar...</x-slot>
                <option value="">Seleccione una categoría...</option>
                @foreach ($categories as $id => $name)
                    <option value="{{$id}}" {{ old('category_id', $course->category_id ?? '') == $id ? 'selected' : '' }}>{{$name}}</option>
                @endforeach
            </x-combobox>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nivel
            <x-combobox name="level_id">
                <x-slot name="title">Seleccionar...</x-slot>
                <option value="">Seleccione un nivel...</option>
                @foreach ($levels as $id => $name)
                    <option value="{{$id}}" {{ old('level_id', $course->level_id ?? '') == $id ? 'selected' : '' }}>{{$name}}</option>
                @endforeach
            </x-combobox>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Modalidad</label>
            <x-combobox name="modalidad_id">
                <x-slot name="title">Seleccionar...</x-slot>
                <option value="">Seleccione modalidad...</option>
                @foreach ($modalidades as $id => $name)
                    <option value="{{$id}}" {{ old('modalidad_id', $course->modalidad_id ?? '') == $id ? 'selected' : '' }}>{{$name}}</option>
                @endforeach
            </x-combobox>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Periodo</label>
            <x-combobox name="periodo_id">
                <x-slot name="title">Seleccionar...</x-slot>
                <option value="">Seleccione periodo...</option>
                @foreach ($periodos as $id => $name)
                    <option value="{{$id}}" {{ old('periodo_id', $course->periodo_id ?? '') == $id ? 'selected' : '' }}>{{$name}}</option>
                @endforeach
            </x-combobox>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sede</label>
            <x-combobox name="sede_id">
                <x-slot name="title">Seleccionar...</x-slot>
                <option value="">Seleccione sede...</option>
                @foreach ($sedes as $id => $name)
                    <option value="{{$id}}" {{ old('sede_id', $course->sede_id ?? '') == $id ? 'selected' : '' }}>{{$name}}</option>
                @endforeach
            </x-combobox>
        </div>

    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Imagen del curso</label>
        <x-upload-file name="image"></x-upload-file>
    </div>
</div>

<script>
    function courseForm() {
        return {
            title: '{{ old('title', $course->title ?? '') }}',
            subtitle: '{{ old('subtitle', $course->subtitle ?? '') }}',
            description: '{{ old('description', $course->description ?? '') }}',
            errors: {},
            validateTitle() {
                if (this.title.length < 5) {
                    this.errors.title = 'El título debe tener al menos 5 caracteres.';
                } else {
                    delete this.errors.title;
                }
            },
            validateSubtitle() {
                if (this.subtitle.length > 0 && this.subtitle.length < 5) {
                    this.errors.subtitle = 'El subtítulo debe tener al menos 5 caracteres.';
                } else {
                    delete this.errors.subtitle;
                }
            },
            validateDescription() {
                if (this.description.length > 0 && this.description.length < 5) {
                    this.errors.description = 'La descripción debe tener al menos 5 caracteres.';
                } else {
                    delete this.errors.description;
                }
            },
            initForm() {
                if (this.title.length > 0) this.validateTitle();
                if (this.subtitle.length > 0) this.validateSubtitle();
                if (this.description.length > 0) this.validateDescription();
            }
        }
    }
</script>
