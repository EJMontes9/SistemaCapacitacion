@vite(['resources/js/courses-view.js'])
<x-app-layout>
    <div>
        <x-course.hero-view :course="$course"  :name="$name_user"/>
    </div>
    <div>
        <x-course.list-section-view :section="$section" :lesson="$lesson" :course="$course" :evaluation="$evaluation"/>
    </div>
</x-app-layout>
