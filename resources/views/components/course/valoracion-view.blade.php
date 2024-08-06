<div class="flex flex-col">
    <div class="w-full p-4 bg-white rounded-lg shadow mt-4 h-[33rem] justify-center items-center">
        <label for="surveyQuestion" class="block text-sm font-medium text-gray-700">Seleccione una lección:</label>
        <x-combobox id="surveyQuestion" class="form-select mt-1 block w-full">
            <option value="" selected>Seleccione una opción</option>
        </x-combobox>
        <canvas id="myLineChart" class="mt-4"></canvas>
    </div>
    <div class="w-full p-4 bg-white rounded-lg shadow mt-4">
        <p class="text-2xl">Calificación general del curso</p>
        <canvas id="myBarChart" class="mt-4"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lineCtx = document.getElementById('myLineChart').getContext('2d');
        const barCtx = document.getElementById('myBarChart').getContext('2d');

        const defaultLineData = [0, 0, 0, 0, 0];
        const defaultLineBackgroundColor = 'rgba(75, 192, 192, 0.2)';
        const defaultLineBorderColor = 'rgba(75, 192, 192, 1)';

        const lineData = {
            labels: ['1 estrella', '2 estrellas', '3 estrellas', '4 estrellas', '5 estrellas'],
            datasets: [{
                label: 'Survey Responses',
                data: defaultLineData,
                backgroundColor: defaultLineBackgroundColor,
                borderColor: defaultLineBorderColor,
                borderWidth: 1,
                fill: false
            }]
        };

        const lineConfig = {
            type: 'line',
            data: lineData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Survey Responses'
                    }
                }
            },
        };

        let myLineChart = new Chart(lineCtx, lineConfig);

        const barData = {
            labels: ['1 Estrellas', '2 Estrellas', '3 Estrellas', '4 Estrellas', '5 Estrellas'],
            datasets: [{
                label: 'Number of Responses',
                data: [0, 0, 0, 0, 0],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        const barConfig = {
            type: 'bar',
            data: barData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: true,
                        text: 'Rating Distribution'
                    }
                }
            },
        };

        let myBarChart = new Chart(barCtx, barConfig);

        fetch(`/api/survey-questions/{{$courseId}}`)
            .then(response => response.json())
            .then(questions => {
                const surveyQuestionSelect = document.getElementById('surveyQuestion');
                questions.forEach(question => {
                    const option = document.createElement('option');
                    option.value = question.id;
                    option.textContent = question.lesson;
                    option.dataset.question = question.question;
                    surveyQuestionSelect.appendChild(option);
                });

                surveyQuestionSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const surveyId = selectedOption.value;
                    const surveyQuestion = selectedOption.dataset.question;

                    fetch(`/api/survey-responses/${surveyId}`)
                        .then(response => response.json())
                        .then(data => {
                            myLineChart.data.labels = data.labels;
                            myLineChart.data.datasets[0].data = data.data;
                            myLineChart.update();
                        });
                });
            });

        fetch(`/api/survey-ratings/{{$courseId}}`)
            .then(response => response.json())
            .then(data => {
                myBarChart.data.datasets[0].data = data;
                myBarChart.update();
            });
    });
</script>