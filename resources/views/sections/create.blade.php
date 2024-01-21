<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Crea un nuevo curso</h1>
        <x-validation-errors />
        <form action="{{route('sections.store')}}" method="post" enctype="multipart/form-data">
            <x-course.form-courses :categories="$categories" :levels="$levels" />
            <x-button class="mt-4" type="submit">Save</x-button>
        </form>
    </div>
</x-app-layout>
