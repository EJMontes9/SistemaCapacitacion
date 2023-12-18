<x-app-layout>
    @foreach($courses as $course)
        <x-course.cardcourse :course="$course"/>
    @endforeach
</x-app-layout>
