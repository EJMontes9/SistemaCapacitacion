<!-- resources/views/components/course/valoracion-view.blade.php -->

<!-- Add this inside the Blade template where you want the combo box and pie chart to appear -->
<div class="grid grid-cols-2">
    <div class="p-4 bg-white rounded-lg shadow mt-4">
        <label for="surveyQuestion">Select a Lesson:</label>
        <x-combobox id="surveyQuestion" class="form-select mt-1 block w-full"></x-combobox>
        <canvas id="myPieChart" class="mt-4"></canvas>
    </div>
</div>


<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('myPieChart').getContext('2d');
        const data = {
            labels: ['Yes', 'No'],
            datasets: [{
                label: 'Survey Responses',
                data: [0, 0],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
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

        let myPieChart = new Chart(ctx, config);

        // Fetch survey questions from the API
        fetch('/api/survey-questions')
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
                            if (data.total === 0) {
                                myPieChart.data.datasets[0].data = [0, 0];
                                myPieChart.data.datasets[0].backgroundColor = ['rgba(128, 128, 128, 0.2)', 'rgba(128, 128, 128, 0.2)'];
                                myPieChart.data.datasets[0].borderColor = ['rgba(128, 128, 128, 1)', 'rgba(128, 128, 128, 1)'];
                                myPieChart.options.plugins.title.text = `${surveyQuestion} (Not yet responded)`;
                            } else {
                                myPieChart.data.datasets[0].data = [data.yes, data.no];
                                myPieChart.data.datasets[0].backgroundColor = ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'];
                                myPieChart.data.datasets[0].borderColor = ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'];
                                myPieChart.options.plugins.title.text = `${surveyQuestion} (Yes: ${(data.yes / data.total * 100).toFixed(2)}%, No: ${(data.no / data.total * 100).toFixed(2)}%)`;
                            }
                            myPieChart.update();
                        });
                });
            });
    });
</script>