<?php
	$invested = floatval(file_get_contents("invested.txt"));
	$sold = floatval(file_get_contents("sold.txt"));

	if(isset($_GET["spentMoney"])){
		$invested = $invested + floatval($_GET["spentMoney"]);
		file_put_contents("invested.txt",$invested);
	}

	if(isset($_GET["soldItem"])){
		$sold = $sold + floatval($_GET["soldItem"]);
		file_put_contents("sold.txt",$sold);
	}

	$invested = floatval(file_get_contents("invested.txt"));
	$sold = floatval(file_get_contents("sold.txt"));
	$netted = floatval($sold-$invested);
?>
<html>
<!-- HTML ------------------------------------------------------>
	<head>
		<title>ElectroPi Orders</title>

		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
	        <link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>


		<script src="js/jquery.js"></script>
        	<script src="js/jquery-ui.js"></script>

		<style>
			body{
				font-family: 'Dosis', sans-serif;
				color:#aaaaaa;
				background-color:#181818;
				margin:20px;
			}
			#title{
				font-family: 'Oswald', sans-serif;
				font-size:36px;
			}
			.sectionLink{
				font-size:24px;
				color:#00ffbe;
			}
		</style>
	</head>
	<body>
		<div id="title">ElectroPi Orders</div><br>

		<div id="homeMenu">
			<div class="sectionLink" onclick="switchView('newOrder','New Order');">NEW ORDER</div><br>
			<div class="sectionLink" style="color:#ff5c93;">CURRENT ORDERS</div>
			<br>
			<table width="640">
				<tr>
					<td>
						<div class="money">INVESTED: <div id="invested">$<?php echo $invested;?></div></div>
					</td>
					<td>
						<div class="money">SOLD: <div id="sold">$<?php echo $sold;?></div></div>
					</td>
					<td>
						<div class="money">NETTED: <div id="netted">$<?php echo $netted;?></div></div>
					</td>
				</tr>
			</table>
		</div>

		<div id="newOrder" style="display:none;">
			<div class="back" onclick="switchView('homeMenu','ElectroPi Orders');">
				BACK TO MAIN
			</div><br>
			NEW ORDER PAGE SHOWN HERE
		</div>

		<div id="currentOrders" style="display:none;">
			 ORDERS SHOWN HERE
		</div>
	</body>

<!-- JS -------------------------------------------------------->
	<script>
		window.currentView = "homeMenu";

		function switchView(newView,title){
			$("#title").html(title);
			$("#"+window.currentView).fadeOut("fast",function(){
                                $("#"+newView).fadeIn("fast",function(){
                                });
                        });
			window.currentView = newView;
		}

	</script>
</html>
