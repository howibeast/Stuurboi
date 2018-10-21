<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
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
var chart = AmCharts.makeChart("chartdiv", {
    "theme": "light",
    "type": "serial",
    "startDuration": 2,
    "dataProvider": [{
        "name": "Ntuthuko Zikalala",
        "rating": 5,
        "color": "#FF0F00"
    }, {
        "name": "Tsholo Mashiane",
        "rating": 4.9,
        "color": "#FF6600"
    }, {
        "name": "Minenhle Sibanda",
        "rating": 3.9,
        "color": "#FF9E01"
    }, {
        "name": "Hloni Mphuti",
        "rating": 4.2,
        "color": "#FCD202"
    }, 
     {
        "name": "Velile Vamba",
        "rating": 5,
        "color": "#F8FF01"
    },{
        "name": "Thokozani Mtshali",
        "rating": 5,
        "color": "#F8FF01"
    }, {
        "name": "Zama Zikalala",
        "rating": 4.5,
        "color": "#B0DE09"
    }],
    "valueAxes": [{
        "position": "left",
        "axisAlpha":0,
        "gridAlpha":0,
        "title": "Ratings per driver"
    }],
    "graphs": [{
        "balloonText": "[[category]]: <b>[[value]]</b>",
        "colorField": "color",
        "fillAlphas": 0.85,
        "lineAlpha": 0.1,
        "type": "column",
        "topRadius":1,
        "valueField": "rating"
    }],
    "depth3D": 40,
	"angle": 30,
    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "name",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha":0,
        "gridAlpha":0

    }

}, 0);
</script>


<!-- HTML -->
<div class="tableHeader col-lg-9">
<h3 class="text-center"> Average Ratings Per Driver </h3>     
    <div class="row" style="background-color: #ffffff;">
<div id="chartdiv" class="col-md-9 col-xs-12" style="background:white"></div>
    </div>
</div>