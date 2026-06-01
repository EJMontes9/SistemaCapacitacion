@vite(['resources/js/courses-view.js'])
<x-app-layout>
    <div>
        <x-course.hero-view :course="$course" :name="$name_user"/>
    </div>

    <div class="w-full">
        <!-- botonera de los tabs -->
        <div class="flex justify-center mb-4">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <!-- Tab 1 - Contenido -->
                    <a href="#" role="tab" data-tab="contenido"
                       class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none text-indigo-600 hover:text-indigo-800"
                       aria-current="page">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Contenido del curso</span>
                    </a>
                    <!-- Tab 2 Estadísticas Generales -->
                    @hasanyrole('Instructor|Admin')
                    @if(Auth::user()->id == $course->user_id)
                        <a href="#" role="tab" data-tab="progreso"
                           class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                            <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Estadísticas progreso</span>
                        </a>
                    @endif
                    @endhasanyrole
                    <!-- Tab 3 Estadísticas para el Instructor -->
                    <a href="#" role="tab" data-tab="desempeno"
                       class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Estadísticas Desempeño</span>
                    </a>
                    <a href="#" role="tab" data-tab="valoraciones"
                       class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Valoraciones</span>
                    </a>
                    <a href="#" role="tab" data-tab="multimedia"
                       class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Multimedia</span>
                    </a>
                    <a href="#" role="tab" data-tab="anuncios"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">
                            Anuncios
                            @php
                                $annActiveCount = $announcements->filter(function($a) { return !$a->expires_at || $a->expires_at->isFuture(); })->count();
                            @endphp
                            @if($annActiveCount > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full ml-1" style="font-size:10px;">{{ $annActiveCount }}</span>
                            @endif
                        </span>
                    </a>
                    <a href="#" role="tab" data-tab="tareas"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Tareas</span>
                    </a>
                    <a href="#" role="tab" data-tab="certificado"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none">
                        <span class="bg-white rounded-full group-hover:bg-gray-50 group-focus:ring-4 group-focus:ring-indigo-500 group-focus:ring-opacity-50 py-2 px-4">Certificado</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Contenido de los tabs -->
        <div class="mt-6">
            <!-- Tab 1 Content -->
            <div role="tabpanel" data-tab="contenido" class="p-4 bg-white rounded-lg shadow">
                <x-course.list-section-new-view :sections="$sections" :lessons="$lessons" :resources="$resources"
                                                :course="$course" :evaluation="$evaluation"/>
            </div>
            <!-- Tab 2 Content -->
            @hasanyrole('Instructor|Admin')
            @if(Auth::user()->id == $course->user_id)
                <div role="tabpanel" data-tab="progreso" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                    <x-course.coursestats-view :course="$course"/>
                </div>
            @endif
            @endhasanyrole
            <!-- Tab 3 Content -->
            <div role="tabpanel" data-tab="desempeno" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                <x-course.coursegeneralstats-view :course="$course"/>
            </div>


            <!-- Tab 4 Content -->
            <div role="tabpanel" data-tab="valoraciones" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                <x-course.valoracion-view :courseId="$course->id"/>
            </div>

            <!-- Tab 5 - Multimedia -->
            <div role="tabpanel" data-tab="multimedia" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Archivos Multimedia</h2>
                @if ($mediaFiles->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($mediaFiles as $file)
                            <div class="border rounded-lg p-4 hover:shadow-md transition">
                                @if (str_starts_with($file->type, 'image'))
                                    <div class="w-full h-32 flex items-center justify-center rounded mb-2 bg-gray-50">
                                        <img src="{{ $file->thumbnail_url ?? $file->url }}" alt="{{ $file->original_name }}"
                                             class="max-w-full max-h-full object-contain">
                                    </div>
                                @elseif (str_starts_with($file->type, 'video'))
                                    <div class="w-full h-32 bg-indigo-100 flex items-center justify-center rounded mb-2">
                                        <svg class="w-12 h-12 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                                    </div>
                                @elseif (str_starts_with($file->type, 'audio'))
                                    <div class="w-full h-32 bg-green-100 flex items-center justify-center rounded mb-2">
                                        <svg class="w-12 h-12 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"/></svg>
                                    </div>
                                @elseif ($file->type === 'document')
                                    <div class="w-full h-32 bg-yellow-50 flex items-center justify-center rounded mb-2">
                                        <svg class="w-12 h-12 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                    </div>
                                @else
                                    <div class="w-full h-32 bg-gray-100 flex items-center justify-center rounded mb-2">
                                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/></svg>
                                    </div>
                                @endif
                                <p class="text-sm font-medium truncate">{{ $file->title ?? $file->original_name }}</p>
                                @if ($file->description)
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $file->description }}</p>
                                @endif
                                @php
                                    $typeLabels = ['image' => 'Imagen', 'video' => 'Video', 'audio' => 'Audio', 'document' => 'Documento', 'other' => 'Otro'];
                                @endphp
                                <p class="text-xs text-gray-400 mt-1">{{ $file->size_formatted }} · {{ $typeLabels[$file->type] ?? 'Otro' }}</p>
                                <div class="flex gap-2 mt-2">
                                    <a href="{{ route('admin.media.download', $file) }}"
                                       class="text-xs text-blue-600 hover:underline">Descargar</a>
                                    <a href="{{ route('admin.media.stream', $file) }}" target="_blank"
                                       class="text-xs text-green-600 hover:underline">Ver</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p>No hay archivos multimedia en este curso.</p>
                    </div>
                @endif
            </div>

            <!-- Tab 6 - Anuncios -->
            <div role="tabpanel" data-tab="anuncios" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Anuncios del Curso</h2>
                @if ($announcements->count() > 0)
                    <div class="space-y-4">
                        @foreach ($announcements as $announcement)
                            <div class="border rounded-lg p-4 {{ $announcement->priority === 'urgent' ? 'border-red-300 bg-red-50' : ($announcement->priority === 'high' ? 'border-orange-300 bg-orange-50' : 'border-gray-200') }}">
                                <div class="flex items-start justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $announcement->priority === 'urgent' ? 'bg-red-100 text-red-800' : ($announcement->priority === 'high' ? 'bg-orange-100 text-orange-800' : ($announcement->priority === 'low' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800')) }}">
                                        {{ ucfirst($announcement->priority) }}
                                    </span>
                                </div>
                                <div class="mt-2 text-gray-700 prose prose-sm max-w-none">
                                    {!! nl2br(e($announcement->content)) !!}
                                </div>
                                <div class="mt-3 text-xs text-gray-500">
                                    Publicado {{ $announcement->published_at ? $announcement->published_at->format('d/m/Y H:i') : $announcement->created_at->format('d/m/Y H:i') }}
                                    por {{ $announcement->user->name ?? 'Sistema' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <p>No hay anuncios para este curso.</p>
                    </div>
                @endif
            </div>

            <!-- Tab 7 - Tareas -->
            <div role="tabpanel" data-tab="tareas" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Tareas del Curso</h2>
                @if($assignments->count() > 0)
                    <div class="space-y-4">
                        @foreach($assignments as $assignment)
                            @php $mySub = $assignment->submissions->where('user_id', Auth::id())->first(); @endphp
                            <div class="border rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-900">{{ $assignment->title }}</h3>
                                        @if($assignment->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $assignment->description }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        @if($assignment->due_date) Límite: {{ $assignment->due_date->format('d/m/Y H:i') }} @endif
                                    </span>
                                </div>
                                <div class="mt-2 flex items-center gap-3 text-sm">
                                    @if($mySub)
                                        <span class="text-green-600 font-medium"><i class="fas fa-check-circle"></i> Entregado
                                            @if($mySub->score !== null) — Calificación: {{ $mySub->score }}/{{ $assignment->max_score }} @endif
                                        </span>
                                        @if($mySub->feedback) <span class="text-gray-500">— {{ $mySub->feedback }}</span> @endif
                                    @else
                                        <span class="text-yellow-600 font-medium"><i class="fas fa-clock"></i> Pendiente</span>
                                        @if($assignment->due_date && now()->greaterThan($assignment->due_date) && !$assignment->allow_late)
                                            <span class="text-red-600 font-medium"><i class="fas fa-ban"></i> Vencida</span>
                                        @elseif($assignment->due_date && now()->greaterThan($assignment->due_date) && $assignment->allow_late)
                                            <span class="text-orange-600 font-medium"><i class="fas fa-exclamation-triangle"></i> Atrasada permitida</span>
                                            <a href="#" class="text-blue-600 hover:underline" onclick="event.preventDefault();document.getElementById('subForm{{ $assignment->id }}').classList.toggle('hidden')">Entregar</a>
                                        @else
                                            <a href="#" class="text-blue-600 hover:underline" onclick="event.preventDefault();document.getElementById('subForm{{ $assignment->id }}').classList.toggle('hidden')">Entregar</a>
                                        @endif
                                    @endif
                                </div>
                                @if(!$mySub)
                                    @if(!($assignment->due_date && now()->greaterThan($assignment->due_date) && !$assignment->allow_late))
                                        <form id="subForm{{ $assignment->id }}" method="POST" action="{{ route('assignments.submit', $assignment) }}" enctype="multipart/form-data" class="hidden mt-3 p-3 bg-gray-50 rounded-lg">
                                            @csrf
                                            <div class="form-group">
                                                <textarea name="text_content" class="w-full rounded border-gray-300 text-sm" rows="3" placeholder="Escribe tu respuesta (opcional)"></textarea>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="file" name="file" class="form-control-file text-sm">
                                                <small class="text-muted">Archivo opcional (PDF, DOC, ZIP, imagen — máx 50MB)</small>
                                            </div>
                                            <button type="submit" class="mt-2 px-4 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"><i class="fas fa-upload"></i> Entregar</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p>No hay tareas asignadas para este curso.</p>
                    </div>
                @endif
            </div>

            <!-- Tab 8 - Certificado -->
            <div role="tabpanel" data-tab="certificado" class="p-4 bg-white rounded-lg shadow mt-4 hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Certificado</h2>
                @php
                    $certUser = Auth::user();
                    $certRecord = \App\Models\Certificate::where('course_id', $course->id)->where('user_id', $certUser->id)->where('status', 'approved')->first();
                    $certTemplate = \App\Models\CertificateTemplate::where('course_id', $course->id)->first();
                @endphp
                @if($certRecord)
                    <div class="text-center py-6">
                        <svg class="mx-auto h-16 w-16 text-green-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-lg font-semibold text-gray-800">¡Felicidades! Has completado el curso.</p>
                        <p class="text-sm text-gray-500 mt-1">Tu certificado está disponible.</p>
                        <a href="{{ route('admin.certificates.download', $certRecord) }}" class="inline-block mt-4 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-download"></i> Descargar Certificado
                        </a>
                        <p class="text-xs text-gray-400 mt-2">Código: <strong>{{ $certRecord->certificate_code }}</strong></p>
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <p class="text-gray-500">Completa el curso para obtener tu certificado.</p>
                        @php
                            $minAtt = $certTemplate?->min_attendance ?? \App\Models\Setting::getValue('cert_min_attendance', 80);
                            $minGrade = $certTemplate?->min_grade ?? \App\Models\Setting::getValue('cert_min_grade', 70);
                        @endphp
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg inline-block text-left text-sm text-gray-600">
                            <p class="font-semibold text-blue-800 mb-1">Requisitos:</p>
                            <ul class="list-disc list-inside">
                                <li>Asistencia mínima: <strong>{{ $minAtt }}%</strong></li>
                                <li>Calificación mínima: <strong>{{ $minGrade }}/100</strong></li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- // script que hacen funcionar los tabs --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('a[role="tab"]');
            const panels = document.querySelectorAll('div[role="tabpanel"]');

            function showTab(tabName) {
                tabs.forEach(function(t) { t.classList.remove('text-indigo-600'); });
                panels.forEach(function(p) { p.classList.add('hidden'); });
                var activeTab = document.querySelector('a[role="tab"][data-tab="' + tabName + '"]');
                var activePanel = document.querySelector('div[role="tabpanel"][data-tab="' + tabName + '"]');
                if (activeTab) activeTab.classList.add('text-indigo-600');
                if (activePanel) activePanel.classList.remove('hidden');
            }

            tabs.forEach(function(tab) {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    var tabName = this.getAttribute('data-tab') || 'contenido';
                    showTab(tabName);
                });
            });

            showTab('contenido');
        });
    </script>


</x-app-layout>

