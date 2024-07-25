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

<h3 class="text-lg font-medium leading-6 text-gray-900">Progreso General</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div id="progresoGeneralChart" style="height: 400px;"></div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div id="progresoSeccionesChart" style="height: 400px;"></div>
    </div>
</div>




<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Estadísticas de Encuestas - Curso: Desarrollo Web Fullstack</h1>
    
    <div id="calificacionesChart2"  style="height: 600px;" class="space-y-8">
            <!-- Los gráficos se insertarán aquí dinámicamente -->
    </div>
</div>

<script>
    // Función para obtener los datos de la API
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

// Función para crear el gráfico de calificaciones
function crearGraficoCalificaciones(data) {
    Highcharts.chart('calificacionesChart2', {
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

// Inicialización del gráfico
document.addEventListener('DOMContentLoaded', () => {
    var courseId = document.getElementById('identificador').getAttribute('data-course');
    fetchCalificacionesData(courseId)
        .then(calificacionesData => {
            if (calificacionesData) {
                crearGraficoCalificaciones(calificacionesData);
            } else {
                document.getElementById('calificacionesChart2').innerHTML = '<p class="text-red-500">Error al cargar los datos de calificaciones.</p>';
            }
        });
});


// Datos de ejemplo para el progreso general del curso
const progresoGeneralData = {
    completado: 80,
    restante: 20
};

// Función para crear el gráfico de progreso general
function crearGraficoProgresoGeneral(data) {
    Highcharts.chart('progresoGeneralChart', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
        title: {
            text: 'Progreso<br>General<br>del Curso',
            align: 'center',
            verticalAlign: 'middle',
            y: 60
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                dataLabels: {
                    enabled: true,
                    distance: -50,
                    style: {
                        fontWeight: 'bold',
                        color: 'white'
                    }
                },
                startAngle: -90,
                endAngle: 90,
                center: ['50%', '75%'],
                size: '110%'
            }
        },
        series: [{
            type: 'pie',
            name: 'Progreso del Curso',
            innerSize: '50%',
            data: [
                ['Completado', data.completado],
                ['Restante', data.restante]
            ]
        }]
    });
}

// Inicialización del gráfico
document.addEventListener('DOMContentLoaded', () => {
    crearGraficoProgresoGeneral(progresoGeneralData);
});



// Datos de ejemplo para el progreso de las secciones del curso
const progresoSeccionesData = [
    { name: 'Sección 1', progreso: 100 },
    { name: 'Sección 2', progreso: 80 },
    { name: 'Sección 3', progreso: 60 },
    { name: 'Sección 4', progreso: 40 },
    { name: 'Sección 5', progreso: 20 }
];

// Función para crear el gráfico de progreso de secciones
function crearGraficoProgresoSecciones(data) {
    Highcharts.chart('progresoSeccionesChart', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Progreso por Secciones del Curso'
        },
        xAxis: {
            categories: data.map(item => item.name),
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            max: 80,
            title: {
                text: 'Progreso (%)',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' %'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Progreso',
            data: data.map(item => item.progreso)
        }]
    });
}

// Inicialización del gráfico
document.addEventListener('DOMContentLoaded', () => {
    crearGraficoProgresoSecciones(progresoSeccionesData);
});
</script>