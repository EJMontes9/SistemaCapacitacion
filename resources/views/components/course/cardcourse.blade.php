@php
    $user = Auth::user();
@endphp
<div class="shadow-xl shadow-gray-300 bg-gray-300 px-3 py-6 rounded-xl w-[18rem] h-[24rem] mb-10 flex flex-col">
  <div class="bg-gray-400 rounded-tr-xl rounded-bl-xl w-full h-64 flex items-center justify-center overflow-hidden">
    <img class="w-full h-full object-cover" src="images/courses/{{ $course->image }}" alt="Card image cap">
  </div>
  <div class="card-body flex-grow">
    <h5 class="font-bold text-black my-2 text-xl">{{ $course->title }}</h5>
    <p class="text-black">{{ $course->description }}</p>
    <x-button class="mt-5">
      <a href="{{route('courses.show', $course->slug)}}" class="btn btn-primary mx-1">Ver detalles</a>
    </x-button>
      @if(!$course->users->contains($user))
          <x-button class="mt-5">
              <a href="{{ route('courses.add', $course->id) }}" class="btn btn-primary mx-1">Agregar curso</a>
          </x-button>
      @endif
  </div>
</div>
