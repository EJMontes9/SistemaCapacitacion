<x-app-layout>
    <div class="flex flex-col sm:flex-row w-full justify-center items-center">
        @foreach($courses as $course)
            <x-course.cardcourse :course="$course"/>
        @endforeach
    </div>
</x-app-layout>
