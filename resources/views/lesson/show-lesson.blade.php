@vite(['resources/js/courses-view.js'])
<x-app-layout>
    <div class="flex flex-column">
        <div class="w-full mt-9">
            <h1 class="text-2xl font-semibold text-gray-900 mb-4">{{$thislesson->name}}</h1>
            <div class="p-4 w-[500px] h-[400px]">
                {!! $thislesson->iframe !!}
            </div>

            <div class="w-full">
                <x-course.list-section-view :section="$section" :lesson="$lesson" :course="$course" :evaluation="$evaluation"/>
            </div>
        </div>
    </div>
</x-app-layout>
