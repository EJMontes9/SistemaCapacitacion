<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">editar de Lecciones</h1>
        <x-validation-errors />
        <form action="{{ route('lessons.update', ['lesson' => $lesson->id]) }}" method="post" >
            @method('PUT')
            @csrf
            <x-lesson.form-lessons :platform="$platform" :section="$section" :lessons="$lesson"/>
            <x-button class="mt-4" type="submit">Save</x-button>
        </form>
    </div>
</x-app-layout>
