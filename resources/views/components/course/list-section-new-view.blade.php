<!-- component -->
<?php
$numSection = 1;
// @props(['sections', 'resources', 'lessons', 'course', 'evaluation'])
?>
@props(['sections', 'lessons', 'resources', 'course', 'evaluation'])

<div>
    <div class="flex flex-col p-0 pb-6 m-h-screen">
        <div class="flex flex-col gap-1 lg:p-0 p-0 rounde-lg m-0">
            <div class="flex flex-row justify-between items-center lg:text-2xl md:text-xl text-lg lg:pb-3 p-0 font-black text-gray-700">
                <h2>Secciones del curso:</h2>
                @hasanyrole('Instructor|Admin')
                <a href="{{route('courses.paso2', $course)}}" class="mr-4">
                    <i class="fa-solid fa-pen text-blue-900"></i>
                </a>
                @endhasanyrole
            </div>

            @foreach ($sections as $section)
                <div class="bg-gray-200 bg-opacity-100 rounded-xl p-2 mb-8">
                    <div class="flex bg-gray-400 bg-opacity-50 hover:bg-gray-100 items-center justify-between w-full p-2 cursor-pointer border-2 rounded-xl"
                         id="seccion-list">
                        <div class="flex flex-row justify-between items-center w-full">
                            <div class="flex flex-row flex-wrap ml-2">
                                <div class="text-xs text-gray-800 w-full">Seccion #{{ $loop->iteration }}:</div>
                                <div class="text-sm my-auto leading-3 text-gray-700 font-bold w-full">{{$section->name}}</div>
                            </div>
                        </div>
                    </div>

                    @php
                        $filteredEvaluations = $evaluation->filter(function($eval) use ($section) {
                            return $eval->module_id == $section->id;
                        });
                    @endphp

                    <x-course.list-lesson-new-view
                            :lessons="$lessons[$section->id] ?? collect()"
                            :resources="$resources"
                            :section="$section"
                            :course="$course"
                            :evaluation="$filteredEvaluations"
                    />
                </div>
            @endforeach
        </div>
    </div>
</div>