<!-- component -->
<?php
$numSection =1;
?>
<div>
    <div class="flex flex-col p-2 py-6 m-h-screen">
        <div class="flex flex-col gap-4 lg:p-4 p-2  rounde-lg m-2">
            <div class="lg:text-2xl md:text-xl text-lg lg:p-3 p-1 font-black text-gray-700">Secciones del curso</div>
            @foreach ($section as $sections)
                <div class="flex items-center justify-between w-full p-2 lg:rounded-full md:rounded-full hover:bg-gray-100 cursor-pointer border-2 rounded-lg" id="seccion-list">
                    <div class="lg:flex md:flex items-center">
                        <div class="h-12 w-12 mb-2 lg:mb-0 border md:mb-0 rounded-full mr-3"></div>
                        <div class="flex flex-col">
                            <div class="text-sm leading-3 text-gray-700 font-bold w-full">Tema #{{$numSection}}</div>
                            <div class="text-xs text-gray-600 w-full">{{$sections->name}}</div>
                        </div>
                    </div>
                    <svg class="h-6 w-6 mr-1 invisible md:visible lg:visible xl:visible" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
            <x-course.list-lesson-view :lesson="$lesson" :sections="$sections"></x-course.list-lesson-view>
                    <?php $numSection++;?>
            @endforeach
        </div>
    </div>
</div>
