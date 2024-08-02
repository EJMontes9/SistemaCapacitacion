<div class="flex flex-col">
    <div class="flex flex-row">
        <div class="p-4 bg-white rounded-lg shadow w-1/4  justify-center items-center mr-4">
            <div id="dataCourse" data-courseId="{{ $course->id }}"></div>
            <canvas id="completionChart" width="400" height="400"></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        </div>
        <div class="p-4 bg-white rounded-lg shadow w-3/4  justify-center items-center mr-4">
            
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
    });
</script>
