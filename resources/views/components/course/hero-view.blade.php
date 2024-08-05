<!-- component -->
<link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
<link rel="stylesheet"
      href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">


<section class="relative bg-blueGray-50">
    <div class="items-center flex flex-wrap">
        <div class="w-full md:w-4/12 ml-auto mr-auto px-4">
            @if($course->image)
                <img alt="..." class="max-w-full rounded-lg shadow-lg"
                     src="{{ asset('images/courses/' . $course->image) }}">
            @else
                <img alt="..." class="max-w-full rounded-lg shadow-lg"
                     src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
            @endif

        </div>
        <div class="w-full md:w-5/12 ml-auto mr-auto px-4">
            @hasanyrole('Instructor|Admin')
            @if(Auth::user()->id == $course->user_id)
                <div class="flex flex-row justify-end items-center">
                    <a href="{{route('courses.edit', $course)}}" class=" mr-4"><i
                                class="fa-solid fa-pen text-blue-900"></i></a>
                    <form action="{{route('courses.destroy',$course)}}" method="post">
                        @csrf
                        @method('delete')
                        <button class="mt-4 btn btn-danger">
                            <i class="fa-solid fa-trash text-red-600"></i>
                        </button>
                    </form>
                </div>
            @endif
            @endhasanyrole
            <div class="md:pr-12">
                <h3 class="text-3xl font-semibold">{{$course->title}}</h3>
                <p class="mt-4 text-lg leading-relaxed text-blueGray-500">
                    {{$course->description}}
                </p>
                <div class="d-none" id="identificador" data-course="{{$course->id}}"></div>
                <ul class="list-none mt-6">
                    <li class="py-2">
                        <div class="flex items-center">
                            <div>
                                <span
                                        class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-cyan-600 bg-cyan-200 mr-3"><i
                                            class="fas fa-fingerprint"></i></span>
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
                                <span
                                        class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-cyan-600 bg-cyan-200 mr-3"><i
                                            class="fab fa-html5"></i></span>
                            </div>
                            <div>
                                <h4 class="text-blueGray-500">{{$course->category->name}}</h4>
                            </div>
                        </div>
                    </li>
                    <li class="py-2">
                        <div class="flex items-center">
                            <div>
                                <span
                                        class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-cyan-600 bg-cyan-200 mr-3"><i
                                            class="far fa-paper-plane"></i></span>
                            </div>
                            <div>
                                <h4 class="text-blueGray-500">{{$course->level->name}}</h4>
                            </div>
                        </div>
                    </li>

                </ul>

                <div class="block w-full overflow-x-auto my-3">
                    <div id="course-progress" class="divide-y divide-gray-100">
                        <!-- Dynamic content will be inserted here -->
                    </div>
                </div>

                <!-- Add this button to trigger the popup -->
                <button id="rateCourseButton"
                        class="fixed bottom-4 right-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-full flex items-center justify-center shadow-lg transition duration-300 ease-in-out transform hover:scale-110">
                    <i class="fa fa-star text-2xl"></i>
                    <span class="ml-2 hidden hover:inline-block">Valorar</span>
                </button>
            </div>
        </div>
    </div>

    <!-- HTML para el popup -->
    <div id="rateCoursePopup" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
            <h2 class="text-2xl text-gray-900 mb-4">Valoraciones</h2>
            <div id="lesson-rating" class="mt-4">
                <!-- El widget de calificación se insertará aquí dinámicamente -->
            </div>
            <button id="closePopupButton"
                    class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Cerrar
            </button>
        </div>
    </div>

</section>

<!-- Add this button to trigger the popup -->


<!-- Include the JavaScript to handle the popup and fetch the rating widget -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rateCourseButton = document.getElementById('rateCourseButton');
        const rateCoursePopup = document.getElementById('rateCoursePopup');
        const closePopupButton = document.getElementById('closePopupButton');
        const ratingContainer = document.getElementById('lesson-rating');
        const courseId = {{ $course->id }};
        const userId = {{ Auth::user()->id }};

        fetch(`/api/course/${courseId}/surveys`)
            .then(response => response.json())
            .then(data => {
                if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                    rateCourseButton.classList.remove('hidden');
                    rateCourseButton.addEventListener('click', function () {
                        rateCoursePopup.classList.remove('hidden');
                        const survey = data.data[0]; // Assuming there's only one survey for simplicity
                        if (survey) {
                            showRatingWidget(survey);
                        } else {
                            ratingContainer.innerHTML = '<p>No se encontraron datos de calificaciones.</p>';
                        }
                    });
                } else {
                    rateCourseButton.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching surveys:', error);
                rateCourseButton.classList.add('hidden');
            });

        closePopupButton.addEventListener('click', function () {
            rateCoursePopup.classList.add('hidden');
        });

        function showRatingWidget(ratingData) {
            const {averageRating = 0, ratingCount = 0, userRating = null, survey_id} = ratingData;
            ratingContainer.innerHTML = `
        <div class="flex flex-col items-center">
            <div class="flex items-center space-x-1">
                ${[1, 2, 3, 4, 5].map(star => `
                    <button type="button" class="star ${star <= (userRating ?? Math.round(averageRating)) ? 'text-yellow-400' : 'text-gray-300'}" data-value="${star}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.39 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.39-2.46a1 1 0 00-1.175 0l-3.39 2.46c-.784.57-1.838-.197-1.54-1.118l1.287-3.97a1 1 0 00-.364-1.118l-3.39-2.46c-.784-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z"/>
                        </svg>
                    </button>
                `).join('')}
            </div>
            <p class="text-sm mt-2">Promedio: ${averageRating.toFixed(1)} (${ratingCount} valoraciones)</p>
            <p class="text-sm hidden">Tu calificación: ${userRating ?? 'No has calificado aún'}</p>
        </div>
        `;

            ratingContainer.querySelectorAll('.star').forEach(star => {
                star.addEventListener('click', function () {
                    const rating = this.dataset.value;
                    submitRating(rating, survey_id);
                });
            });
        }

        function submitRating(rating, surveyId) {
            fetch('/api/lesson-ratings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    user_id: userId,
                    lesson_id: courseId,
                    survey_id: surveyId,
                    rating: rating
                })
            })
                .then(response => response.json())
                .then(data => {
                    showRatingWidget({
                        averageRating: data.averageRating,
                        ratingCount: data.ratingCount,
                        userRating: data.userRating,
                        survey_id: surveyId
                    });
                    setTimeout(() => {
                        rateCoursePopup.classList.add('hidden'); // Close the modal after 2 seconds
                    }, 2000);
                })
                .catch(error => console.error('Error submitting rating:', error));
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const courseId = '{{ $course->id }}';
        const userId = '{{ Auth::id() }}';
        if (!userId) {
            console.error('User is not authenticated.');
            return;
        }

        fetch(`/api/course-progress/${courseId}/${userId}`)
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then(data => {
                const progressContainer = document.getElementById('course-progress');
                progressContainer.innerHTML = ''; // Clear existing content

                const progress = data.progress;
                const progressBar = document.createElement('div');
                progressBar.classList.add('text-gray-500');

                progressBar.innerHTML = `
                    <div class="flex items-center">
                        <span class="mr-2 text-xs font-medium">${progress}%</span>
                        <div class="relative w-full">
                            <div class="w-full bg-gray-200 rounded-sm h-2">
                                <div class="bg-cyan-600 h-2 rounded-sm" style="width: ${progress}%"></div>
                            </div>
                        </div>
                    </div>
                `;

                progressContainer.appendChild(progressBar);
            })
            .catch(error => console.error('Error fetching course progress:', error));
    });

</script>
