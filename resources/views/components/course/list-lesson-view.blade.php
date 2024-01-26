<ul class="lesson-menu hidden" id="lesson-list">
    @if(!isset($lesson) || count($lesson) == 0 )
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl">
                <p class="ml-4">Sin lecciones asignadas</p>
            </div>
        </li>
    @else
            <?php $numLesson =1; ?>
        @foreach ($lesson[$sections] as $lessons)
            <li>
                <div class="flex flex-row justify-between items-center border-2 ml-12 py-2 rounded-xl">
                    <a class="ml-4" href="{{route('courses.showLesson', ['id_lesson' => $lessons->id, 'slug' => last(explode('/', request()->path()))])}}">{{$numLesson++}}. {{$lessons->name}}</a>
                    @hasanyrole('Instructor|Admin')
                    <div class="px-3 flex flex-row justify-center items-center">
                        <a href="{{ route('lessons.edit',$lessons) }}" class="mr-4">
                            <i class="fa-solid fa-pen text-blue-900"></i>
                        </a>
                        <form action="{{route('lessons.destroy',$lessons)}}" method="post" class="flex flex-row items-center">
                            @csrf
                            @method('delete')
                            <button type="submit" class="mx-0 p-0 mt-4 ">
                                <i class="fa-solid fa-trash text-red-600"></i>
                            </button>
                        </form>
                    </div>
                    @endhasanyrole
                </div>
            </li>
        @endforeach
    @endif
        @hasanyrole('Instructor|Admin')
        <li>
            <div class="flex flex-row justify-between border-2 ml-12 py-2 rounded-xl mt-3">
                <a href="{{route('lessons.create', $courseid)}}" class="ml-4">Agregar leccion</a>
            </div>
        </li>
        @endhasanyrole
</ul>

