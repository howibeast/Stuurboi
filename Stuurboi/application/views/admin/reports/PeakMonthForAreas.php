<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}												
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/pie.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

<!-- Chart code -->
<script>
var chart = AmCharts.makeChart( "chartdiv", {
  "type": "pie",
  "theme": "light",
   "titles": [ {
    "text": "Peak Month Per Place Per Month",
    "size": 16
  } ],
  "dataProvider": [ {
    "month": "Sandton : May",
    "value": 6,
    
  }, {
    "month": "Soweto : April",
    "value": 15
  }, {
    "month": "Fourways : February",
    "value": 30
  }, {
    "month": "Midrand : April",
    "value": 39
  }, {
    "month": "Tembisa : February",
    "value": 15
  }, {
    "month": "East Rand : May",
    "value": 10
  } 
  , {
    "month": "Randburg : August",
    "value": 10
  }, {
    "month": "Kempton Park : June",
    "value": 10
  }, {
    "month": "City Center : March",
    "value": 40
  }],
  "valueField": "value",
  "titleField": "month",
  "outlineAlpha": 0.4,
  "depth3D": 15,
  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
  "angle": 30
} );
</script>

<!-- HTML -->
<div class="tableHeader col-lg-9">
<h3 class="text-center"> Peak Months Per Place </h3>     
    <div class="row" style="background-color: #ffffff;">
<div id="chartdiv" class="col-md-9 col-xs-12" style="background:#a6a7a8"></div>
    </div>
</div>