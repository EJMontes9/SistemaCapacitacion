<main>
    <div class="pt-6 px-4">
        <div class="w-full grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 gap-4">
            <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8  2xl:col-span-2">
                <x-graficos>
                    <x-slot name="title">
                        Modulos completados
                    </x-slot>
                    <x-slot name="categories">
                        ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
                    </x-slot>
                </x-graficos>
            </div>
            <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                <x-lista-cursos></x-lista-cursos>
            </div>
        </div>
        <div class="mt-4 w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            <x-indicadores-generales>
                <x-slot name="title">
                    Angular
                </x-slot>
                <x-slot name="calificacion">
                    4.5
                </x-slot>
                <x-slot name="porcentaje">
                    40.2
                </x-slot>
                <x-slot name="mejora">
                    false
                </x-slot>
            </x-indicadores-generales>
            <x-indicadores-generales>
                <x-slot name="title">
                    laravel
                </x-slot>
                <x-slot name="calificacion">
                    10
                </x-slot>
                <x-slot name="porcentaje">
                    15.2
                </x-slot>
                <x-slot name="mejora">
                    true
                </x-slot>
            </x-indicadores-generales>
            <x-indicadores-generales>
                <x-slot name="title">
                    Springboot
                </x-slot>
                <x-slot name="calificacion">
                    10
                </x-slot>
                <x-slot name="porcentaje">
                    55.2
                </x-slot>
                <x-slot name="mejora">
                    true
                </x-slot>
            </x-indicadores-generales>
        </div>
        <div class="grid grid-cols-1 2xl:grid-cols-2 xl:gap-4 my-4">
            <x-sugerencia-temas></x-sugerencia-temas>
            <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                <x-progreso-cursos></x-progreso-cursos>
            </div>
        </div>
    </div>
</main>

<!--<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
    <x-application-logo class="block h-12 w-auto" />

    <h1 class="mt-8 text-2xl font-medium text-gray-900">
        Indicadores
    </h1>

    <p class="mt-6 text-gray-500 leading-relaxed">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nec volutpat justo, pellentesque bibendum purus.
        Phasellus rhoncus sagittis euismod. Vivamus quis aliquam purus. Nunc eu lorem et nisl rutrum eleifend.
        Cras imperdiet tincidunt metus sed suscipit. Pellentesque non elementum libero. Ut tempus augue nec est consectetur,
        vel consequat ex pharetra. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
        Ut ipsum est, aliquam ac tempor ac, aliquet et velit. Mauris commodo eget nunc in laoreet.
    </p>
    <x-graficos>
        <x-slot name="title">
            Modulos completados
        </x-slot>
        <x-slot name="categories">
            ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
        </x-slot>
    </x-graficos>
</div>-->

