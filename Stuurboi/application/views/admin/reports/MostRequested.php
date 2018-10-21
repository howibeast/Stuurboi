<!-- Styles -->
<style>
#chartdiv {
	width		: 75%;
	height		: 500px;
	font-size	: 11px;
}					
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

<!-- Chart code -->
<script>
var chart = AmCharts.makeChart( "chartdiv", {
  "type": "serial",
  "theme": "light",
  "dataProvider": [ {
    "Area": "UJ",
    "visits": 69
  }, {
    "Area": "WITS",
    "visits": 25
  }, {
    "Area": "Braamfontein",
    "visits": 24
  }, {
    "Area": "Wesdene",
    "visits": 20
  }, {
    "Area": "Brixton",
    "visits": 10
  }, {
    "Area": "Rossmore",
    "visits": 5
  } ],
  "valueAxes": [ {
    "gridColor": "#FFFFFF",
    "gridAlpha": 0.2,
    "dashLength": 0,
     "title": "Numer of Request"
  } ],
  "gridAboveGraphs": true,
  "startDuration": 1,
  "graphs": [ {
    "balloonText": "[[category]]: <b>[[value]]</b>",
    "fillAlphas": 0.8,
    "lineAlpha": 0.2,
    "type": "column",
    "valueField": "visits"
  } ],
  "chartCursor": {
    "categoryBalloonEnabled": false,
    "cursorAlpha": 0,
    "zoomable": false
  },
  "categoryField": "Area",
  "categoryAxis": {
    "gridPosition": "start",
    "gridAlpha": 0,
    "tickPosition": "start",
    "tickLength": 20
  }

} );
</script>

<!-- HTML -->
<div class="tableHeader col-lg-9">
<h3 class="text-center"> Most Requested Places </h3>     
    <div class="row" style="background-color: #ffffff;">
<div id="chartdiv" class="col-md-9 col-xs-12" style="background:white"></div>
    </div>
</div>