<x-app-layout>
    @if (session('success'))

        <div id="success-message" class="flex bg-green-100 rounded-lg p-4 mb-4 text-sm text-green-700 alert alert-success" role="alert">
            <svg class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <div>
                <span class="font-medium">Insersion correcta!</span> {{ session('success') }}
            </div>
        </div>
    @endif
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Creacion de Lecciones</h1>
        <x-validation-errors />
        <form action="{{route('lessons.store')}}" method="post" >
            @csrf
            <x-lesson.form-lessons :platform="$platform" :section="$section" />
            <x-button class="mt-4" type="submit">Save</x-button>
        </form>
    </div>
</x-app-layout>

<script>
    setTimeout(function() {
        const element = document.getElementById('success-message');
        if (element) {
            element.style.display = 'none';
        }
    }, 4000); // 4000ms = 4s
</script>
