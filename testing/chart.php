<html>
	<head>
		<script type="text/javascript" src="js/smoothie.js"></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript">

			function ready(){
				var smoothie = new SmoothieChart({maxValue:100,minValue:0});
				var data_from_ajax;

				smoothie.streamTo(document.getElementById("mycanvas"),1000 /*delay*/);
				// Data
				var line1 = new TimeSeries();

				// Add a random value to each line every second
				setInterval(function() {
					$.get('cpuListen.php', function(data) {
						data_from_ajax = data;
					});
					line1.append(new Date().getTime(), parseInt(data_from_ajax));
				}, 1000);

				// Add to SmoothieChart
				smoothie.addTimeSeries(line1);
				}
		</script>
	</head>
	<body onload="ready()">
		<canvas id="mycanvas" width="400" height="100"></canvas>
	</body>
</html>
