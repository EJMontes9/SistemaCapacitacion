<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

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