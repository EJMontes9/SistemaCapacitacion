<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-900 mb-4">{{$course->title}}</h1>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <figure>
                @if($course->image)
                    <img class="w-full h-80 object-cover object-center" src="{{ asset('images/courses/' . $course->image) }}" alt="">
                @else
                    <img class="w-full h-80 object-cover object-center" src="https://images.pexels.com/photos/4497761/pexels-photo-4497761.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940" alt="">
                @endif
            </figure>
            <x-label>
                <p class="font-bold text-gray-800 text-3xl my-3">Descripcion</p>
                <p class="text-gray-600">{{$course->description}}</p>
            </x-label>
            <x-label>Nivel: {{$course->level->name}}</x-label>
            <x-label>Categoria: {{$course->category->name}}</x-label>

            <h2 class="text-2xl font-bold text-gray-800 mt-4 mb-2">Lecciones del curso</h2>
            <div>
                <ul>
                    @foreach ($section as $sections)
                        <?php $numSection =1;?>
                        <li class="text-gray-600 mb-4 section-item">
                            <x-label>
                                {{$sections->name}}
                            </x-label>
                            <ul class="lesson-menu">
                                @foreach ($lesson[$sections->id] as $lessons)
                                    <li>
                                        <a href="{{route('lessons.show', $lessons->id)}}">{{$numSection++}}. {{$lessons->name}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>

