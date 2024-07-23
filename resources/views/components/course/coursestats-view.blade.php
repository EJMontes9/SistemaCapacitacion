<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<style>
#promedioGrafico {
    width: 100%;
    height: 500px;
}
</style>

<h3 class="text-lg font-medium leading-6 text-gray-900">Valoración del Curso</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div id="calificacionesChart" style="height: 400px;"></div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div id="valoracionesChart" style="height: 400px;"></div>
    </div>
</div>




<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Estadísticas de Encuestas - Curso: Desarrollo Web Fullstack</h1>
    
    <div id="chartsContainer" class="space-y-8">
            <!-- Los gráficos se insertarán aquí dinámicamente -->
    </div>
</div>

{{-- scripts de estadisticas de encuentas --}}
<script>
    const courseId = document.getElementById('identificador').getAttribute('data-course');

    async function fetchSurveyData() {
        try {
            const response = await fetch(`http://sistemacapacitacion.test/api/course-statistics/${courseId}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error("Error fetching survey data:", error);
            return null;
        }
    }

    function prepareChartData(sectionData) {
        const categories = [];
        const yesData = [];
        const noData = [];
        const titles = [];

        Object.entries(sectionData).forEach(([lesson, responses]) => {
            categories.push(lesson);
            yesData.push(parseInt(responses.yes));
            noData.push(parseInt(responses.no));
            titles.push(responses.survey_title);
        });

        return { categories, yesData, noData, titles };
    }

    function renderChart(containerId, sectionName, sectionData) {
        const { categories, yesData, noData, titles } = prepareChartData(sectionData);

        Highcharts.chart(containerId, {
            chart: {
                type: 'column'
            },
            title: {
                text: sectionName
            },
            xAxis: {
                categories: categories,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Respuestas'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Sí',
                data: yesData,
                color: '#50B432'
            }, {
                name: 'No',
                data: noData,
                color: '#DF5353'
            }]
        });

        // Agregar los títulos de las encuestas debajo del gráfico
        const titlesContainer = document.createElement('div');
        titlesContainer.className = 'mt-4';
        titles.forEach((title, index) => {
            const titleElement = document.createElement('p');
            titleElement.className = 'text-sm mb-2';
            titleElement.innerHTML = `<strong>${categories[index]}:</strong> ${title}`;
            titlesContainer.appendChild(titleElement);
        });
        document.getElementById(containerId).parentNode.appendChild(titlesContainer);
    }

    function createCharts(data) {
        const container = document.getElementById('chartsContainer');
        container.innerHTML = ''; // Limpiar el contenedor

        Object.entries(data).forEach(([sectionName, sectionData], index) => {
            const sectionDiv = document.createElement('div');
            sectionDiv.className = 'bg-white p-6 rounded-lg shadow-md mb-8';
            sectionDiv.innerHTML = `<div id="chart-${index}" style="height: 400px;"></div>`;
            container.appendChild(sectionDiv);

            renderChart(`chart-${index}`, sectionName, sectionData);
        });
    }

    async function initializeCharts() {
        const surveyData = await fetchSurveyData();
        if (surveyData) {
            createCharts(surveyData);
        } else {
            document.getElementById('chartsContainer').innerHTML = '<p class="text-red-500">Error al cargar los datos de la encuesta.</p>';
        }
    }

    // Asegurarnos de que Highcharts esté cargado antes de renderizar los gráficos
    function checkHighchartsAndRender() {
        if (typeof Highcharts !== 'undefined') {
            initializeCharts();
        } else {
            setTimeout(checkHighchartsAndRender, 50);
        }
    }

    // Iniciar el proceso de renderizado cuando la página esté cargada
    document.addEventListener('DOMContentLoaded', checkHighchartsAndRender);
</script>
{{-- Fin scripts de estadisticas de encuentas --}}


{{-- scripts de estadisticas de estudiantes --}}
<script>// Función para obtener los datos de la API
    function fetchCalificacionesData(courseId) {
        return fetch(`http://sistemacapacitacion.test/api/course/${courseId}/grades`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => result.data)
            .catch(error => {
                console.error("Error fetching data:", error);
                return null;
            });
    }
    
    // Función para obtener los datos de valoración del curso
    function fetchValoracionesData(courseId) {
        return fetch(`http://sistemacapacitacion.test/api/course-statistics-rating/${courseId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error("Error fetching valoraciones data:", error);
                return null;
            });
    }
    
    // Función para crear el gráfico de calificaciones
    function crearGraficoCalificaciones(data) {
        Highcharts.chart('calificacionesChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Distribución de Calificaciones'
            },
            xAxis: {
                categories: Object.keys(data),
                title: {
                    text: 'Rango de Calificaciones'
                }
            },
            yAxis: {
                title: {
                    text: 'Número de Estudiantes'
                }
            },
            series: [{
                name: 'Estudiantes',
                data: Object.values(data),
                color: '#4299E1'
            }]
        });
    }
    
    // Función para procesar los datos de valoraciones
    function procesarDatosValoraciones(data) {
        const series = [
            { name: '1 Estrella', data: [] },
            { name: '2 Estrellas', data: [] },
            { name: '3 Estrellas', data: [] },
            { name: '4 Estrellas', data: [] },
            { name: '5 Estrellas', data: [] }
        ];
        const categories = [];
    
        Object.entries(data).forEach(([seccion, lecciones]) => {
            Object.entries(lecciones).forEach(([leccion, valoraciones]) => {
                categories.push(`${seccion} - ${leccion}`);
                for (let i = 0; i < 5; i++) {
                    series[i].data.push(parseInt(valoraciones[i + 1]) || 0);
                }
            });
        });
    
        return { series, categories };
    }
    
    // Función para crear el gráfico de valoraciones
    function crearGraficoValoraciones(data) {
        const { series, categories } = procesarDatosValoraciones(data);
    
        Highcharts.chart('valoracionesChart', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Valoraciones por Secciones y Lecciones'
            },
            xAxis: {
                categories: categories,
                title: {
                    text: null
                },
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Número de Valoraciones',
                    align: 'high'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: 'gray'
                    }
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: series
        });
    }
    
    // Función principal que inicializa los gráficos
    function initializeCharts2(courseId) {
        fetchCalificacionesData(courseId)
            .then(calificacionesData => {
                if (calificacionesData) {
                    crearGraficoCalificaciones(calificacionesData);
                } else {
                    document.getElementById('calificacionesChart').innerHTML = '<p class="text-red-500">Error al cargar los datos de calificaciones.</p>';
                }
            });
    
        fetchValoracionesData(courseId)
            .then(valoracionesData => {
                if (valoracionesData) {
                    crearGraficoValoraciones(valoracionesData);
                } else {
                    document.getElementById('valoracionesChart').innerHTML = '<p class="text-red-500">Error al cargar los datos de valoraciones.</p>';
                }
            });
    }
    
    // Crear los gráficos cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', () => {
        var courseId = document.getElementById('identificador').getAttribute('data-course');
        initializeCharts2(courseId);
    });
</script>

{{-- Fin scripts de estadisticas de estudiantes --}}