<x-app-layout>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Editar curso de {{ $course->title }}</h1>
        <x-validation-errors />
        <form action="{{route('courses.update',$course->id )}}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <x-course.form-courses :categories="$categories" :levels="$levels" :course="$course" />
            <x-button class="mt-4" type="submit">Save</x-button>
        </form>
    </div>
</x-app-layout>
