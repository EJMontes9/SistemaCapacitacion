<!DOCTYPE html>
<html lang="lang="{{ str_replace('_', '-', app()->getLocale()) }}"">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SistemaCapacitaciones</title>

    @vite('resources/css/app.css')

</head>

<body>
    <!--<div>
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                                                    <a href="{{ url('/dashboard') }}" class="font-semibold text-red-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
@else
    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Iniciar sesión</a>
                                                    @if (Route::has('register'))
    <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Registarse</a>
    @endif
                    @endauth
                </div>
            @endif
        </div>-->

    <!-- component -->
    <!-- component -->
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- navbar -->
    <header class="absolute inset-x-0 top-0 z-50 py-6">
        <div class="mx-auto lg:max-w-7xl w-full px-5 sm:px-10 md:px-12 lg:px-5">
            <nav class="w-full flex justify-between gap-6 relative">
                <!-- logo -->
                <div class="min-w-max inline-flex relative">
                    <a href="/" class="relative flex items-center gap-3">
                        <div class="inline-flex text-lg font-semibold text-gray-900">
                            StudyApp
                        </div>
                    </a>
                </div>

                <!-- option -->
                <div data-nav-overlay aria-hidden="true"
                    class="fixed hidden inset-0 lg:!hidden bg-gray-800/60 bg-opacity-50 backdrop-filter backdrop-blur-xl">
                </div>
                <div data-navbar
                    class="flex invisible opacity-0  translate-y-10 overflow-hidden lg:visible lg:opacity-100  lg:-translate-y-0 lg:scale-y-100 duration-300 ease-linear flex-col gap-y-6 gap-x-4 lg:flex-row w-full lg:justify-between lg:items-center absolute lg:relative top-full lg:top-0 bg-white lg:!bg-transparent border-x border-x-gray-100 lg:border-x-0">
                    <ul
                        class="border-t border-gray-100  lg:border-t-0 px-6 lg:px-0 pt-6 lg:pt-0 flex flex-col lg:flex-row gap-y-4 gap-x-3 text-lg text-gray-700 w-full lg:justify-center lg:items-center">
                        <li>
                            <a href="#" class="duration-300 font-medium ease-linear hover:text-blue-600 py-3">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="/listcourse" class="duration-300 font-medium ease-linear hover:text-blue-600 py-3">
                                Cursos
                            </a>
                        </li>
                        <li>
                            <a href="#" class="duration-300 font-medium ease-linear hover:text-blue-600 py-3">
                                Contactanos
                            </a>
                        </li>
                    </ul>

                    <!-- auth -->
                    @if (Route::has('login'))
                        <div
                            class="lg:min-w-max flex items-center sm:w-max w-full pb-6 lg:pb-0 border-b border-gray-100   lg:border-0 px-6 lg:px-0">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="flex justify-center items-center w-full sm:w-max px-6 h-12 rounded-full outline-none relative overflow-hidden border duration-300 ease-linear
                                    after:absolute after:inset-x-0 after:aspect-square after:scale-0 after:opacity-70 after:origin-center after:duration-300 after:ease-linear after:rounded-full after:top-0 after:left-0 after:bg-skin-hoverPrimary hover:after:opacity-100 hover:after:scale-[2.5] bg-skin-primary border-transparent hover:border-[#172554]">
                                    <span class="relative z-10 text-white h">
                                        Dashboard
                                    </span>
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="flex justify-center items-center w-full sm:w-max px-6 h-12 rounded-full outline-none relative overflow-hidden border duration-300 ease-linear
                                        after:absolute after:inset-x-0 after:aspect-square after:scale-0 after:opacity-70 after:origin-center after:duration-300 after:ease-linear after:rounded-full after:top-0 after:left-0 after:bg-skin-secondary hover:after:opacity-100 hover:after:scale-[2.5]  border-transparent ">
                                    <span class="relative z-10 text-skin-secondary font-bold ">
                                        Iniciar sesion
                                    </span>
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="flex justify-center items-center w-full sm:w-max px-6 h-12 rounded-full outline-none relative overflow-hidden border duration-300 ease-linear
                                            after:absolute after:inset-x-0 after:aspect-square after:scale-0 after:opacity-70 after:origin-center after:duration-300 after:ease-linear after:rounded-full after:top-0 after:left-0 after:bg-skin-hoverPrimary hover:after:opacity-100 hover:after:scale-[2.5] bg-skin-primary border-transparent hover:border-[#172554]">
                                        <span class="relative z-10 text-white h">
                                            Registrarse
                                        </span>
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>


                <div class="min-w-max flex items-center gap-x-3">

                    <button data-toggle-navbar data-is-open="false"
                        class="lg:hidden lg:invisible outline-none w-7 h-auto flex flex-col relative">
                        <span id="line-1"
                            class="w-6 h-0.5 rounded-full bg-gray-700 transition-all duration-300 ease-linear"></span>
                        <span id="line-2"
                            class="w-6 origin-center  mt-1 h-0.5 rounded-ful bg-gray-700 transition-all duration-300 ease-linear"></span>
                        <span id="line-3"
                            class="w-6 mt-1 h-0.5 rounded-ful bg-gray-700 transition-all duration-300 ease-linear"></span>
                        <span class="sr-only">togglenav</span>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- hero section -->
    <section class="relative py-32 lg:py-36 bg-white">
        <div
            class="mx-auto lg:max-w-7xl w-full px-5 sm:px-10 md:px-12 lg:px-5 flex flex-col lg:flex-row gap-10 lg:gap-12">
            <div class="absolute w-full lg:w-1/2 inset-y-0 lg:right-0 hidden lg:block">
                <span
                    class="absolute -left-6 md:left-4 top-24 lg:top-28 w-24 h-24 rotate-90 skew-x-12 rounded-3xl bg-green-400 blur-xl opacity-60 lg:opacity-95 lg:block hidden"></span>
                <span class="absolute right-4 bottom-12 w-24 h-24 rounded-3xl bg-blue-600 blur-xl opacity-80"></span>
            </div>
            <span
                class="w-4/12 lg:w-2/12 aspect-square bg-gradient-to-tr from-blue-600 to-green-400 absolute -top-5 lg:left-0 rounded-full skew-y-12 blur-2xl opacity-40 skew-x-12 rotate-90"></span>
            <div
                class="relative flex flex-col items-center text-center lg:text-left lg:py-7 xl:py-8
            lg:items-start lg:max-w-none max-w-3xl mx-auto lg:mx-0 lg:flex-1 lg:w-1/2">

                <h1
                    class="text-3xl leading-tight sm:text-4xl md:text-5xl xl:text-6xl
            font-bold text-gray-900">
                    Cursos <span
                        class="text-transparent bg-clip-text bg-gradient-to-br from-indigo-600 from-20% via-blue-600 via-30% to-green-600">Excepcionales</span>
                    en un solo lugar.
                </h1>
                <p class="mt-8 text-gray-700">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolores repellat perspiciatis aspernatur
                    quis voluptatum porro incidunt,
                    libero sequi quos eos velit
                </p>

            </div>
            <div class="flex flex-1 lg:w-1/2 lg:h-auto relative lg:max-w-none lg:mx-0 mx-auto max-w-3xl">
                <img src="https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?auto=format&fit=crop&q=80&w=1740&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    alt="Hero image" width="2350" height="2359"
                    class="lg:absolute lg:w-full lg:h-full rounded-3xl object-cover lg:max-h-none max-h-96">
            </div>
        </div>
    </section>

    <style>
        body {
            font-family: "Raleway", sans-serif;
        }

        button[data-toggle-navbar][data-is-open="true"] #line-1 {
            transform: translateY(0.375rem) rotate(40deg);
        }

        button[data-toggle-navbar][data-is-open="true"] #line-2 {
            transform: scaleX(0);
            opacity: 0;
        }

        button[data-toggle-navbar][data-is-open="true"] #line-3 {
            transform: translateY(-0.375rem) rotate(-40deg);
        }
    </style>

    <script>
        const btnHumb = document.querySelector("[data-toggle-navbar]")
        const navbar = document.querySelector("[data-navbar]")
        const overlay = document.querySelector("[data-nav-overlay]")
        if (btnHumb && navbar) {
            const toggleBtnAttr = () => {
                const isOpen = btnHumb.getAttribute("data-is-open")
                btnHumb.setAttribute("data-is-open", isOpen === "true" ? "false" : "true")
                if (isOpen === "false") {
                    overlay.classList.toggle("hidden")
                } else {
                    overlay.classList.add("hidden")
                }
            }
            btnHumb.addEventListener("click", () => {
                navbar.classList.toggle("invisible")
                navbar.classList.toggle("opacity-0")
                navbar.classList.toggle("translate-y-10")
                toggleBtnAttr()
            })

            overlay.addEventListener("click", () => {
                navbar.classList.add("invisible")
                navbar.classList.add("opacity-0")
                navbar.classList.add("translate-y-10")
                toggleBtnAttr()
            })
        }
    </script>

</body>

</html>
