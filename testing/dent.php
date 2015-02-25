<html>
	<head>
		<title>Business Proposal</title>

		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
                <link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>

                <script src="js/jquery.js"></script>
                <script src="js/jquery-ui.js"></script>

                <style>
                        body{
                                font-family: 'Dosis', sans-serif;
                                color:#aaaaaa;
                                background-color:#181818;
                                margin:40px;
				text-transform:uppercase;
                        }
                        .title{
				text-align:center;
                                font-family: 'Oswald', sans-serif;
                                font-size:42px;
                        }
			.slide{
				text-align:center;
				display:none;
			}
			.yesButton{
				color:#181818;
				background-color:#00ffbe;
				border: none;
				font-family: 'Dosis';
				font-size: 24px;
				padding: 10px;
				font-weight: bold;
				margin-top: 20px;
				cursor:pointer;
			}
			.noButton{
				color:#181818;
				background-color:#ff5c93;
				border: none;
				font-family: 'Dosis';
				font-size: 24px;
				padding: 10px;
				font-weight: bold;
				margin-top: 20px;
				cursor:pointer;
			}
			.content{
				width: 720px;
				font-size: 20px;
				text-align: center;
				margin-left: auto;
				margin-right: auto;
				text-transform:none;
			}
			strong{
				font-weight: bold;
			}
			.highlight{
				color:#00ffbe;
			}
                </style>
	</head>
	<body onload="ready();">

		<div id="welcome" class="slide">
			<div class="title">Andrew, here is a business proposal.</div>
			<div class="content">Since Megaplex and RadioShack are both cutting hours, we really need this.</div>
			<button class="yesButton" onclick="switchView('intro');">PROCEED</button>
		</div>

		<div id="intro" class="slide">
			<div class="title">The first batch of "ElectroPi PRO" I have on the way is 58 boards.</div>
			<div class="content">
				<br>
				<img src="http://connor-n.com/ep_top.png" width="300px"/>
				<img src="http://connor-n.com/ep_bottom.png" width="300px"/><br>
				<br>
				Each costs $12.95 to make. That's everything from the PCB to the components, <span class="highlight">to the box it goes out in.</span> Each sells for $35, making the profit out of each board a total $22.04 in-pocket.<br>
				<br>
				I want you to handle shipping.
			</div>
			<button class="yesButton" onclick="switchView('time');">PROCEED</button>
		</div>

		<div id="time" class="slide">
			<div class="title">Just One Hour a day</div>
			<div class="content">
				<br>
				<img src="http://connor-n.com/ship.jpg" width="600px"/><br>
				<br>
				Once done, they each take about 10 minutes each to package, label, record and ship. Doing 5 boards per day, that's <span class="highlight">50 minutes</span> - leaving you 5 minutes to the post office, 5 min back. (The time it takes to ship each package is included in their 10 minutes' time.) 5 a day, totalling one hour of solid work a day.
			</div>
			<button class="yesButton" onclick="switchView('vacation');">PROCEED</button>
		</div>

		<div id="vacation" class="slide">
			<div class="title">342 Days of Vacation</div>
			<div class="content">
				<br>
				<img src="http://connor-n.com/buffalo.jpg" width="600px"/><br>
				<br>
				Because I still handle all of the soldering, hardware/software design and customer support, I'm thinking <span class="highlight">a 70/30 split for this batch</span>. We can get that closer to 50/50 if you want to develop/support for me as well in the future. With a split like that, you net $348.00 at $36.25/hour <span class="highlight">for only 9.6 hours of work</span> over the duration of selling the batch. Some people will even order two at a time, making that shipment only take 12 minutes or so and earn you twice as much. When I put the first ElectroPi to market, I did a batch of 21 boards on release, and did it in two weeks. <span class="highlight">I sold 78 more</span> before stopping in December. We can do this, it's easy.<br>
				<br>
				After this batch, I'm looking into another batch of 150. With a 50/50 split it would earn you over $1,500 for less than 45 hours of work. Selling 3 boards a day (average) means the batch is done in one month. $1,500 every month. <span class="highlight">You'd be making $18 THOUSAND dollars a year on your own</span>. And in total - you'd only be working 22.5 days a year. 342 days of vacation. If we double efforts we each make $36,000 a year, 320 days of vacation. Want to make $72,000 a year?<br>
				<br>
				You'd still have 275 days worth of vacation.
			</div>
			<button class="yesButton" onclick="switchView('catch');">PROCEED</button>
		</div>

		<div id="catch" class="slide">
			<div class="title">But here's the catch.</div>
			<div class="content">
				<br>
				<img src="http://connor-n.com/catch.jpg" width="600px"/><br>
				<br>
				<span class="highlight">You need to ignore the last few slides.</span> You need to do this because you want to watch your project grow. I want someone who also loves watching charts climb, and will do anything they can to see that happen.
			</div>
			<button class="yesButton" onclick="switchView('kits');">PROCEED</button>
		</div>

		<div id="kits" class="slide">
			<div class="title">And ElectroPi alone won't do this.</div>
			<div class="content">
				<br>
				<img src="http://connor-n.com/kits.jpg" width="600px"/><br>
				<br>
				It WILL die someday, I accept that. So I need another brilliant mind to help make <span class="highlight">the best god damn products we can</span>. All of your things with Tesla coils, TV oscilliscopes, and potato guns? You can design and sell kits for other people to do the same. You don't even have to design something new all of the time because of that factor.
			</div>
			<button class="yesButton" onclick="switchView('dent');">PROCEED</button>
		</div>

		<div id="dent" class="slide">
			<div class="title">Lets put a dent in the fucking universe.</div>
			<div class="content">
				If we can make this work, <span class="highlight">you will never have to work for someone again.</span>
			</div><br>
			<iframe width="853" height="480" src="https://www.youtube.com/embed/v_f_Rm9Z6rw?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
		</div>

	</body>

	<script>
		function ready(){
			setTimeout(function(){
				$("#welcome").fadeIn("slow");
			},500);
	                window.currentView = "welcome";
		}

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
