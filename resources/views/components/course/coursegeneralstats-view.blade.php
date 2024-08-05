<div class="container mx-auto px-4 py-8">
    <div id="promedioCalificacionesChart" class="space-y-8">
        <p class="text-2xl font-bold">Promedio de calificaciones por sección</p>
        @hasanyrole('Instructor|Admin')
            @if(Auth::user()->id == $course->user_id)
                <select id="studentSelector" class="mb-4 p-2 w-1/4 border rounded">
                    <option value="" selected>Seleccione 1 alumno</option>
                    <!-- Options will be populated dynamically -->
                </select>
            @endif
        @else
            <input type="hidden" id="studentId" value="{{ Auth::user()->id }}">
        @endhasanyrole
        <canvas id="promedioCalificacionesChartCanvas" class="w-full h-10"></canvas>
    </div>
</div>

<script>
    let promedioCalificacionesChart;

    function fetchCourseStudents(courseId) {
        return fetch(`/api/course/${courseId}/users`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error("Error fetching students:", error);
                return [];
            });
    }

    function fetchAverageGradesBySection(courseId, userId) {
        return fetch(`/api/course/${courseId}/user/${userId}/average-grades`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error("Error fetching average grades:", error);
                return [];
            });
    }

    function crearGraficoPromedioCalificaciones(data, showEmpty = false) {
        const ctx = document.getElementById('promedioCalificacionesChartCanvas').getContext('2d');
        if (promedioCalificacionesChart) {
            promedioCalificacionesChart.destroy();
        }
        promedioCalificacionesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: showEmpty ? [''] : data.map(item => item.section_name),
                datasets: [{
                    label: 'Promedio de Calificaciones',
                    data: showEmpty ? [0] : data.map(item => item.average_score),
                    borderColor: '#4299E1',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Secciones'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Promedio de Calificaciones'
                        }
                    }
                }
            }
        });
    }

    function actualizarGraficoPromedioCalificaciones(courseId) {
        const selector = document.getElementById('studentSelector');
        selector.addEventListener('change', () => {
            const userId = selector.value;
            if (userId) {
                fetchAverageGradesBySection(courseId, userId)
                    .then(averageGrades => {
                        crearGraficoPromedioCalificaciones(averageGrades);
                    });
            } else {
                crearGraficoPromedioCalificaciones([], true);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        var courseId = document.getElementById('identificador').getAttribute('data-course');
        @hasanyrole('Instructor|Admin')
            @if(Auth::user()->id == $course->user_id)
                fetchCourseStudents(courseId)
                    .then(students => {
                        const selector = document.getElementById('studentSelector');
                        students.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.textContent = student.name;
                            selector.appendChild(option);
                        });

                        crearGraficoPromedioCalificaciones([], true); // Mostrar gráfico vacío inicialmente
                        actualizarGraficoPromedioCalificaciones(courseId);
                    });
            @endif
        @else
            const userId = document.getElementById('studentId').value;
            fetchAverageGradesBySection(courseId, userId)
                .then(averageGrades => {
                    crearGraficoPromedioCalificaciones(averageGrades);
                });
        @endhasanyrole
    });
</script>