<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

</body>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

{{-- redirigir al path principal si no hay sesion --}}
@php $isAuthenticated = Auth::check(); @endphp {{-- Crear una variable para almacenar el estado de autenticación --}}
@if($isAuthenticated && isset(Auth::user()->name)) {{-- la sesion existe --}}  @endif
<script>
    // Obtener el valor de la variable $isAuthenticated desde PHP
    var isAuthenticated = {{ $isAuthenticated ? 'true' : 'false' }};
    // Verificar el estado de autenticación y redirigir si es necesario
    if (!isAuthenticated) {  window.location.href = "{{ route('raiz') }}"; }
</script>


<body class="font-sans antialiased">
    <x-banner />
    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header)) 
            <nav class="bg-white border-b border-gray-200 fixed z-30 w-full">
                <div class="px-3 py-3 lg:px-5 lg:pl-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center justify-start">
                            <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar"
                                class="lg:hidden mr-2 text-gray-600 hover:text-gray-900 cursor-pointer p-2 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 rounded">
                                <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <svg id="toggleSidebarMobileClose" class="w-6 h-6 hidden" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <a href="/dashboard" class="text-xl font-bold flex items-center lg:ml-2.5 w-1/3 h-1/3">
                                <x-application-mark class="block h-9 w-auto  lg:block lg:pl-32" />
                                <span class="self-center whitespace-nowrap ml-3">StudyApp</span>
                            </a>
                            <div class="hidden lg:block lg:pl-32">
                                <div class="mt-1 relative lg:w-64">
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <button id="toggleSidebarMobileSearch" type="button"
                                class="lg:hidden text-gray-500 hover:text-gray-900 hover:bg-gray-100 p-2 rounded-lg">
                                <span class="sr-only">Search</span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        @else
            <nav class="bg-white border-b border-gray-200 fixed z-30 w-full">
                <div class="px-3 py-3 lg:px-5 lg:pl-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center justify-start">
                            <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar"
                                class="lg:hidden mr-2 text-gray-600 hover:text-gray-900 cursor-pointer p-2 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 rounded">
                                <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <svg id="toggleSidebarMobileClose" class="w-6 h-6 hidden" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <a href="" class="text-xl font-bold flex items-center lg:ml-2.5 w-1/3 h-1/3">
                                <x-application-mark class="block h-9 w-auto" />
                                <span class="self-center whitespace-nowrap ml-3">StudyApp</span>
                            </a>
                            <form action="/dashboard" method="GET" class="hidden lg:block lg:pl-32">
                                <div class="mt-1 relative lg:w-64">
                                </div>
                            </form>
                        </div>
                        <div class="flex items-center">
                            <button id="toggleSidebarMobileSearch" type="button"
                                class="lg:hidden text-gray-500 hover:text-gray-900 hover:bg-gray-100 p-2 rounded-lg">
                            </button>
                        </div>
                        {{-- @if (Laravel\Jetstream\Jetstream::managesProfilePhotos()) --}}
                        @if(Auth::user() && Auth::user()->name)
                            <div class="flex">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        @if(isset(Auth::user()->name)) 
                                        {{ Auth::user()->name }}
                                        @else 
                                        @endif
                                    </button>
                                    <button
                                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover"
                                            src="{{ Auth::user()->profile_photo_url }}"
                                            alt="{{ Auth::user()->name }}" />
                                    </button>
                                </span>
                            </div>
                        @else
                            <span class="inline-flex rounded-md">
                                <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                    @if(isset(Auth::user()->name)) 
                                        {{ Auth::user()->name }}
                                        @else 
                                        @endif
                                </button>
                            </span>
                        @endif
                    </div>
                </div>
            </nav>
        @endif

        <!-- Page Content -->
        <main class="flex flex-col min-h-screen">
            <div class="flex-1 overflow-hidden bg-white pt-16 flex flex-col relative">
                <div class="bg-gray-900 opacity-50 hidden fixed inset-0 z-10" id="sidebarBackdrop"></div>
                <div id="main-content" class="flex-1 flex flex-col overflow-y-auto lg:ml-64">
                    <div class="pt-6 px-4">
                        {{ $slot }}
                    </div>
                    <div class="mt-auto">
                        <x-footer></x-footer>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @stack('modals')

    @livewireScripts
    @stack('scripts')

</body>
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="https://demo.themesberg.com/windster/app.bundle.js"></script>

</html>
