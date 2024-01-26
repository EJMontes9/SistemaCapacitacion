<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-900 mb-4">Mis cursos</h1>
    <div class="grid grid-cols-1 justify-items-center sm:flex-row w-full justify-center items-center md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        @if(!($courses->isEmpty()))
            @foreach($courses as $course)
                <x-course.cardcourse :course="$course"/>
            @endforeach
        @else
            <p class="text-gray-900">No tienes cursos por mostrar</p>
        @endif
    </div>
    <div class="mt-4">
        {{--$courses->links()--}}
    </div>
</x-app-layout>
