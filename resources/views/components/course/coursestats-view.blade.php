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

<h3 class="text-lg font-medium leading-6 text-gray-900">Promedio de alumnos</h3>
<p class="mt-2 text-sm text-gray-500">Listado de rendimiento del promedio de los alumnos.</p>
<div id="promedioGrafico"></div>

<h3 class="text-lg font-medium leading-6 text-gray-900">Promedio de alumnos</h3>
<p class="mt-2 text-sm text-gray-500">Listado de rendimiento del promedio de los alumnos.</p>
<div id="chartdiv"></div>




<div class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold mb-6">Estadísticas de Encuestas - Curso: Desarrollo Web Fullstack</h1>
  
  <div id="chartsContainer" class="space-y-8">
      <!-- Los gráficos se insertarán aquí dinámicamente -->
  </div>
</div>


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



<script>
var root = am5.Root.new("promedioGrafico");


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  panX: false,
  panY: false,
  wheelX: "none",
  wheelY: "none"
}));


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var yRenderer = am5xy.AxisRendererY.new(root, {
  minGridDistance: 30
});

yRenderer.grid.template.set("location", 1);

var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
  maxDeviation: 0,
  categoryField: "alumno",
  renderer: yRenderer,
  tooltip: am5.Tooltip.new(root, { themeTags: ["axis"] })
}));

var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0,
  min: 0,
  extraMax: 0.1,
  renderer: am5xy.AxisRendererX.new(root, {
    strokeOpacity: 0.1
  })
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.ColumnSeries.new(root, {
  name: "Series 1",
  xAxis: xAxis,
  yAxis: yAxis,
  valueXField: "value",
  categoryYField: "alumno",
  tooltip: am5.Tooltip.new(root, {
    pointerOrientation: "left",
    labelText: "{valueX}"
  })
}));



// Set data
var data = [
  {
    "alumno": "Luis Guzmán",
    "value": 10.0
  },
  {
    "alumno": "José Fernandez",
    "value": 6.0
  },
  {
    "alumno": "Fernando Rojas",
    "value": 5.8
  },
  {
    "alumno": "Pedro Escamoso",
    "value": 4.3
  },
  {
    "alumno": "Monkey D. Luffy",
    "value": 4.4
  },
  {
    "alumno": "Eren Jaeger",
    "value": 6.1
  },
  {
    "alumno": "Sanji Vinsmoke",
    "value": 2.6
  },
];

yAxis.data.setAll(data);
series.data.setAll(data);
sortCategoryAxis();

// Get series item by category
function getSeriesItem(category) {
  for (var i = 0; i < series.dataItems.length; i++) {
    var dataItem = series.dataItems[i];
    if (dataItem.get("categoryY") == category) {
      return dataItem;
    }
  }
}



// Axis sorting
function sortCategoryAxis() {
  // Sort by value
  series.dataItems.sort(function(x, y) {
    return x.get("valueX") - y.get("valueX"); // descending
    //return y.get("valueY") - x.get("valueX"); // ascending
  })

  // Go through each axis item
  am5.array.each(yAxis.dataItems, function(dataItem) {
    // get corresponding series item
    var seriesDataItem = getSeriesItem(dataItem.get("category"));

    if (seriesDataItem) {
      // get index of series data item
      var index = series.dataItems.indexOf(seriesDataItem);
      // calculate delta position
      var deltaPosition = (index - dataItem.get("index", 0)) / series.dataItems.length;
      // set index to be the same as series data item index
      dataItem.set("index", index);
      // set deltaPosition instanlty
      dataItem.set("deltaPosition", -deltaPosition);
      // animate delta position to 0
      dataItem.animate({
        key: "deltaPosition",
        to: 0,
        duration: 1000,
        easing: am5.ease.out(am5.ease.cubic)
      })
    }
  });

  // Sort axis items by index.
  yAxis.dataItems.sort(function(x, y) {
    return x.get("index") - y.get("index");
  });
}




// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
series.appear(1000);
chart.appear(1000, 100);

</script>