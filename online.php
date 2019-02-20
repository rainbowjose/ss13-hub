<?php
    define("INDEX", "yes");
    include 'core/class.core.php';
    $core = new Core();
    $core->loc('online');
?>
<!DOCTYPE html>
<html>

<head>
	<?php $core->header() ?>
</head>

<body translate="no">
	<?php $core->navbar() ?>
	<div class="charts">
		<div id="chartContainer" style="height: 320px; width: 100%;"></div>
		<script src="/styles/canvasjs.min.js"></script>
        <?php $c = $core->ctheming(); ?>
        <script>
        window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
        	animationEnabled: true,
        	theme: "dark1",
        	zoomEnabled: true,
        	backgroundColor: "<?php echo $c[1]; ?>",
        	axisX:{
        		valueFormatString: "H:mm:ss",
        		lineColor: "<?php echo $c[2]; ?>",
        		labelFontColor: "<?php echo $c[4]; ?>",
        		crosshair: {
        			enabled: true,
        			snapToDataPoint: true,
        			labelBackgroundColor: "<?php echo $c[1]; ?>",
        			labelFontColor: "<?php echo $c[5]; ?>",
        			labelFontSize: 12,
        			color: "<?php echo $c[5]; ?>"
        		}
        	},
        	axisY: {
        		fontSize: 20,
        		maximum: 40,
        		gridColor: "<?php echo $c[2]; ?>",
        		lineColor: "<?php echo $c[2]; ?>",
        		labelFontColor: "<?php echo $c[4]; ?>",
        		crosshair: {
        			enabled: true,
        			snapToDataPoint: false,
        			labelBackgroundColor: "<?php echo $c[1]; ?>",
        			labelFontColor: "<?php echo $c[5]; ?>",
        			labelFontSize: 12,
        			color: "<?php echo $c[5]; ?>"
        		}
        	},
        	toolTip:{
        		shared:true
        	},
        	legend:{
        		cursor:"pointer",
        		fontColor: "<?php echo $c[4]; ?>",
        		verticalAlign: "top",
        		horizontalAlign: "left",
        		dockInsidePlotArea: false,
        		itemclick: toogleDataSeries
        	},
        	data: [{
        		lineThickness: 1,
        		type: "splineArea",
        		fillOpacity: .1,
        		showInLegend: true,
        		name: "Yellow Online",
        		markerType: "circle",
        		xValueFormatString: "YYYY/MM/DD H:mm:ss",
        		color: "<?php echo $c[3]; ?>",
        		dataPoints: [ <?php $core->timeonline(); ?> ]
        	},
        	{
        		type: "splineArea",
        		fillOpacity: .1,
        		showInLegend: true,
        		name: "Yellow Admins",
        		markerType: "circle",
        		xValueFormatString: "YYYY/MM/DD H:mm:ss",
        		color: "<?php echo $c[5]; ?>",
        		dataPoints: [ <?php $core->adminsonline(); ?> ]
        	}]
        });
        chart.render();

        function toogleDataSeries(e){
        	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        		e.dataSeries.visible = false;
        	} else{
        		e.dataSeries.visible = true;
        	}
        	chart.render();
        }

        }
        </script>
	</div>
	<?php $core->footer() ?>
</body>

</html>
