@php
    $primaryColor = \App\Models\Setting::getValue('primary_color', '#3B82F6');
    $secondaryColor = \App\Models\Setting::getValue('secondary_color', '#1E40AF');
    $mainMenu = \App\Models\Menu::where('slug', 'main')->first();
    $menuItems = $mainMenu ? $mainMenu->items : collect();
@endphp

<aside id="sidebar"
       class="fixed hidden z-20 h-full top-0 left-0 pt-16 flex lg:flex flex-shrink-0 flex-col w-64 transition-width duration-75"
       aria-label="Sidebar">
    <div class="relative flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-white pt-0">
        <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
            <div class="flex-1 px-3 bg-white divide-y space-y-1">
                <ul class="space-y-2 pb-2">
                    @foreach($menuItems as $item)
                        @if($item->isVisible())
                            @if($item->children->count() > 0 && $item->children->where('is_active', true)->count() > 0)
                                <li x-data="{ open: false }">
                                    <button @click="open = !open"
                                        class="text-base text-gray-900 font-normal rounded-lg hover:bg-gray-100 flex items-center p-2 group w-full text-left">
                                        @if($item->icon)
                                            <i class="{{ $item->icon }} ml-1 text-gray-500 group-hover:text-gray-900"></i>
                                        @else
                                            <svg class="w-6 h-6 text-gray-500 flex-shrink-0 group-hover:text-gray-900 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                        <span class="ml-3 flex-1 whitespace-nowrap">{{ $item->title }}</span>
                                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <ul x-show="open" class="space-y-1 pl-4 mt-1">
                                        @foreach($item->children as $child)
                                            @if($child->isVisible())
                                                <li>
                                                    <a href="{{ $child->getUrl() }}"
                                                       target="_top"
                                                       class="text-base text-gray-900 font-normal rounded-lg hover:bg-gray-100 flex items-center p-2 group {{ Request::url() === url($child->getUrl()) ? '!text-blue-500 bg-blue-100' : 'text-gray-900' }}">
                                                        <span class="ml-3 flex-1 whitespace-nowrap">{{ $child->title }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $item->getUrl() }}" target="_top"
                                       class="text-base text-gray-900 font-normal rounded-lg hover:bg-gray-100 flex items-center p-2 group {{ Request::url() === url($item->getUrl()) ? '!text-blue-500 bg-blue-100' : 'text-gray-900' }}">
                                        @if($item->icon)
                                            <i class="{{ $item->icon }} ml-1 text-gray-500 group-hover:text-gray-900"></i>
                                        @else
                                            <svg class="w-6 h-6 text-gray-500 flex-shrink-0 group-hover:text-gray-900 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                        <span class="ml-3 flex-1 whitespace-nowrap">{{ $item->title }}</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                </ul>
                <div class="space-y-2 pt-2">
                    <a href="{{ route('profile.show') }}" target="_top"
                       class="text-base font-normal rounded-lg hover:bg-gray-100 flex items-center p-2 group {{ Route::is('profile.show') ? '!text-blue-500 bg-blue-100' : 'text-gray-900' }}">
                        <svg class="w-6 h-6 text-gray-500 flex-shrink-0 group-hover:text-gray-900 transition duration-75"
                             fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3 flex-1 whitespace-nowrap">Mi Perfil</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <a class="text-base bg-white text-red-500 font-normal rounded-lg hover:bg-red-500 hover:text-white flex items-center p-2 group transition duration-200 ease-in-out"
                           href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            <svg class="w-6 h-6 text-red-500 transition duration-200 ease-in-out group-hover:text-white"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-3 flex-1 whitespace-nowrap text-red-500 transition duration-200 ease-in-out group-hover:text-white">Cerrar Sesión</span>
                        </a>
                    </form>

                    <a href="#" target="_blank"
                       class="text-base text-gray-900 font-normal rounded-lg hover:bg-gray-100 group transition duration-75 flex items-center p-2">
                        <svg class="w-6 h-6 text-gray-500 flex-shrink-0 group-hover:text-gray-900 transition duration-75"
                             fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Ayuda</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <style>
        .active-link {
            color: {{ $primaryColor }} !important;
            background-color: {{ $primaryColor }}15 !important;
        }
        .hover\:bg-primary-dynamic:hover {
            background-color: {{ $primaryColor }} !important;
        }
        .text-primary-dynamic {
            color: {{ $primaryColor }} !important;
        }
    </style>
</aside>
