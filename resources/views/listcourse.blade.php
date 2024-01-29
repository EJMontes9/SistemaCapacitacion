<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-900 mb-4">Mis cursos</h1>
    <form method="GET" action="{{ route('courses.index') }}" class="mb-4">
        <div class="relative flex items-center mt-4 md:mt-0">
            <span class="absolute">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-3 text-gray-400 dark:text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </span>
            <input type="text" placeholder="Search" value="{{ request('filter') }}"  name="filter" class="block w-full py-1.5 pr-5 text-gray-700 bg-white border
            border-gray-200 rounded-lg md:w-80 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring
            focus:ring-opacity-40">

            @hasanyrole('Instructor|Admin')
                <div class="relative flex items-start py-4 ml-2">
                    <input id="1" type="checkbox" class="hidden peer" name="filterByUser" value="1" onchange="this.form.submit()" {{ request('filterByUser') ? 'checked' : '' }}>
                    <label for="1" class="inline-flex items-center justify-between w-auto p-2 font-medium tracking-tight border rounded-lg cursor-pointer bg-gray-100 text-brand-black border-gray-200 peer-checked:border-blue-200 peer-checked:bg-blue-200 peer-checked:text-blue-600  peer-checked:decoration-brand-dark decoration-2">
                        <div class="flex items-center justify-center w-full">
                            <div class="text-sm text-brand-black">Cursos Creados</div>
                        </div>
                    </label>
                </div>
            @endhasallroles
        </div>
    </form>
    <div class="grid grid-cols-1 justify-items-center sm:flex-row w-full justify-center items-center md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        @if(!($courses->isEmpty()))
            @foreach($courses as $course)
                <x-course.cardcourse :course="$course"/>
            @endforeach
        @else
            <p class="text-gray-900">No tienes cursos por mostrar</p>
        @endif
    </div>
    <div class="mt-4">
        {{$courses->links()}}
    </div>
</x-app-layout>
