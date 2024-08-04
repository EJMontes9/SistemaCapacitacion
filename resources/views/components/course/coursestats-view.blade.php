<div class="flex flex-col">
    <div class="flex flex-row">
        <div class="p-4 bg-white rounded-lg shadow w-1/4  justify-center items-center mr-4">
            <div id="dataCourse" data-courseId="{{ $course->id }}"></div>
            <canvas id="completionChart" width="400" height="400"></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        </div>
        <div class="p-4 bg-white rounded-lg shadow w-3/4  justify-center items-center mr-4">
            <canvas id="sectionCompletionChart" width="400" height="400"></canvas>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const courseId = document.querySelector('#dataCourse').getAttribute('data-courseId');

        fetch(`/api/course-completion-stats/${courseId}`)
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then(data => {
                const ctx = document.getElementById('completionChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Completo', 'Incompleto'],
                        datasets: [{
                            data: [data.completed, data.incomplete],
                            backgroundColor: ['#4CAF50', '#F44336']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Progreso de los estudiantes en el curso'
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching completion stats:', error));

        fetch(`/api/section-completion-stats/${courseId}`)
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (!Array.isArray(data)) {
                    throw new Error('Invalid data format');
                }

                const sectionCtx = document.getElementById('sectionCompletionChart').getContext('2d');
                const sectionLabels = data.map(section => section.name);
                const sectionData = data.map(section => section.completed_students_count);

                new Chart(sectionCtx, {
                    type: 'bar',
                    data: {
                        labels: sectionLabels,
                        datasets: [{
                            label: 'Estudiantes que han culminado',
                            data: sectionData,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false,
                            },
                            title: {
                                display: true,
                                text: 'Cantidad de estudiantes que han culminado cada sección'
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching section completion stats:', error));
    });
</script>
