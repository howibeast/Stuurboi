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
    "text": "Number of Users Per Month",
    "size": 16
  } ],
  "dataProvider": [ {
    "Month": "March",
    "Users": 3
  }, {
    "Month": "April",
    "Users": 12
  }, {
    "Month": "May",
    "Users": 9
  }, {
    "Month": "June",
    "Users": 50
  }, {
    "Month": "July",
    "Users": 66
  }, {
    "Month": "August",
    "Users": 57
  } ],
  "valueField": "Users",
  "titleField": "Month",
   "balloon":{
   "fixedPosition":true
  }
} );
</script>

<!-- HTML -->
<div class="tableHeader col-lg-9">
<h3 class="text-center"> Users Per Month </h3>     
    <div class="row" style="background-color: #ffffff;">
<div id="chartdiv" class="col-md-9 col-xs-12" style="background:#a6a7a8"></div>
    </div>
</div>