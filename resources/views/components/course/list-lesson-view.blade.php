<ul class="lesson-menu hidden" id="lesson-list">
    @if(!isset($lesson[$sections->id]) || count($lesson[$sections->id]) == 0 )
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                <p class="ml-4">Sin lecciones asignadas</p>
            </div>
        </li>
    @else
            <?php $numLesson =1; ?>
        @foreach ($lesson[$sections->id] as $lessons)
            <li>
                <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                    <a class="ml-4" href="{{route('lessons.show', $lessons->id)}}">{{$numLesson++}}. {{$lessons->name}}</a>
                    <div class="px-3 flex flex-row justify-center items-center">
                        <a href="{{ route('courses.create') }}" class="mr-4">
                            <i class="fa-solid fa-pen text-blue-900"></i>
                        </a>
                        <a href="{{ route('courses.create') }}" class="mr-4">
                            <i class="fa-solid fa-trash text-red-600"></i>
                        </a>
                    </div>
                </div>


            </li>
        @endforeach
    @endif
</ul>

