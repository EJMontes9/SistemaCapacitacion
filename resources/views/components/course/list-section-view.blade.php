<!-- component -->
<?php
$numSection =1;
?>
<div>
    <div class="flex flex-col p-2 py-6 m-h-screen">
        <div class="flex flex-col gap-4 lg:p-4 p-2  rounde-lg m-2">
            <div class=" flex flex-row justify-between items-center lg:text-2xl md:text-xl text-lg lg:p-3 p-1 font-black text-gray-700">
                <h2>
                    Secciones del curso
                </h2>
                <a href="{{ route('sections.create') }}" ><i class="fa-solid fa-plus"></i></a>
            </div>
            @foreach ($section as $sections)
                <div class="flex items-center justify-between w-full p-2 lg:rounded-full md:rounded-full hover:bg-gray-100 cursor-pointer border-2 rounded-lg" id="seccion-list">
                    <div class="flex flex-row justify-between items-center w-full">
                        <div class="flex flex-row w-full">
                            <div class="h-12 w-12 mb-2 lg:mb-0 border md:mb-0 rounded-full mr-3"></div>
                            <div class="flex flex-col">
                                <div class="text-sm leading-3 text-gray-700 font-bold w-full">Tema #{{$numSection}}</div>
                                <div class="text-xs text-gray-600 w-full">{{$sections->name}}</div>
                            </div>
                        </div>
                        <div>
                            <div class="px-3 flex flex-row justify-center items-center">
                                <a href="{{route('sections.edit',$sections)}}" class="mr-4">
                                    <i class="fa-solid fa-pen text-blue-900"></i>
                                </a>
                                <form action="{{route('sections.destroy',$sections->id)}}" method="post" class="flex flex-row items-center">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="mx-0 p-0 mt-4 ">
                                        <i class="fa-solid fa-trash text-red-600"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <x-course.list-lesson-view :lesson="$lesson" :sections="$sections" :courseid="$sections->course_id"></x-course.list-lesson-view>
                    <?php $numSection++;?>
            @endforeach
        </div>
    </div>
</div>
