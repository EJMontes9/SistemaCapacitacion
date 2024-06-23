<!-- component -->
<?php
$numSection =1;
?>
<div>
    <div class="flex flex-col p-0 pb-6 m-h-screen">
        <div class="flex flex-col gap-1 lg:p-0 p-0  rounde-lg m-0">
            <div class=" flex flex-row justify-between items-center lg:text-2xl md:text-xl text-lg lg:pb-3 p-0 font-black text-gray-700">
                <h2>
                    Secciones del curso:
                </h2>
                {{-- @hasanyrole('Instructor|Admin') 
                    @if(Auth::user()->id == $course->user_id) <a href="{{ route('sections.create') }}" ><i class="fa-solid fa-plus"></i></a> @endif
                @endhasanyrole --}}
            </div>
            

            
            @foreach ($section as $sections)
            <div class=" bg-gray-200 bg-opacity-100  rounded-xl p-2  mb-8">
                <div class=" flex bg-gray-400 bg-opacity-50 hover:bg-gray-100 items-center justify-between w-full p-2 cursor-pointer border-2  rounded-xl" id="seccion-list">
                    <div class=" flex flex-row justify-between items-center w-full">
                        {{-- <div class="flex flex-row w-full"> --}}
                            {{-- <div class="h-12 w-12 mb-2 lg:mb-0 border md:mb-0 rounded-full mr-3"></div> --}}
                            <div class="flex flex-row flex-wrap ml-2">
                                <div class="text-xs text-gray-800 w-full">Seccion #{{$numSection}} :</div>
                                <div class="text-sm my-auto leading-3 text-gray-700 font-bold w-full">{{$sections->name}}</div>
                            </div>
                        {{-- </div> --}}
                        @hasanyrole('Instructor|Admin')
                        @if(Auth::user()->id == $course->user_id)
                        <div>
                            <div class="px-3 flex flex-row justify-center items-center">
                                {{-- <a href="{{route('sections.edit',$sections)}}" class="mr-4">
                                    <i class="fa-solid fa-pen text-blue-900"></i>
                                </a> --}}
                                <form action="{{route('sections.destroy',$sections->id)}}" method="post" class="flex flex-row items-center">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="mx-0 p-0 mt-4 ">
                                        <i class="fa-solid fa-trash text-red-600"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                        @endhasanyrole
                    </div>

                    
                </div>

                <x-course.list-lesson-paso2-view :lesson="$lesson" :sections="$numSection" :courseid="$sections->course_id" :section_id="$sections->id" 
                    :evaluation="$evaluation" :sectionsObj="$sections"
                    :usercreate="$course">
                </x-course.list-lesson-paso2-view>
                <?php $numSection++;?>
            </div>
            @endforeach
        </div>
    </div>
</div>
