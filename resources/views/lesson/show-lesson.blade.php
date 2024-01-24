@vite(['resources/js/courses-view.js'])
<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-900 mb-4">{{$lesson->name}}</h1>
    <seccion>
        <div>
            {!! $lesson->iframe !!}
        </div>
    </seccion>
</x-app-layout>
