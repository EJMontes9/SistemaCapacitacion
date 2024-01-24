<!-- component -->
<link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
<link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">


<section class="relative pt-12 bg-blueGray-50">
    <div class="items-center flex flex-wrap">
        <div class="w-full md:w-4/12 ml-auto mr-auto px-4">
            @if($course->image)
                <img alt="..." class="max-w-full rounded-lg shadow-lg" src="{{ asset('images/courses/' . $course->image) }}">
            @else
                <img alt="..." class="max-w-full rounded-lg shadow-lg" src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
            @endif

        </div>
        <div class="w-full md:w-5/12 ml-auto mr-auto px-4">
            <div class="md:pr-12">
                <div class="text-cyan-600 p-3 text-center inline-flex items-center justify-center w-16 h-16 mb-6 shadow-lg rounded-full bg-cyan-300 mt-8">
                    <i class="fas fa-rocket text-xl"></i>
                </div>
                <h3 class="text-3xl font-semibold">{{$course->title}}</h3>
                <p class="mt-4 text-lg leading-relaxed text-blueGray-500">
                    {{$course->description}}
                </p>
                <ul class="list-none mt-6">
                    <li class="py-2">
                        <div class="flex items-center">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-cyan-600 bg-cyan-200 mr-3"><i class="fas fa-fingerprint"></i></span>
                            </div>
                            <div>
                                <h4 class="text-blueGray-500">
                                    {{$name}}
                                </h4>
                            </div>
                        </div>
                    </li>
                    <li class="py-2">
                        <div class="flex items-center">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-cyan-600 bg-cyan-200 mr-3"><i class="fab fa-html5"></i></span>
                            </div>
                            <div>
                                <h4 class="text-blueGray-500">{{$course->category->name}}</h4>
                            </div>
                        </div>
                    </li>
                    <li class="py-2">
                        <div class="flex items-center">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-cyan-600 bg-cyan-200 mr-3"><i class="far fa-paper-plane"></i></span>
                            </div>
                            <div>
                                <h4 class="text-blueGray-500">{{$course->level->name}}</h4>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
