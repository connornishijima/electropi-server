<?php ?>

<html>
	<head>
		<script type="text/javascript" src="http://jqueryrotate.googlecode.com/svn/trunk/jQueryRotate.js"></script>
		<style type="text/css">

@-webkit-keyframes rotate {
  from {
    -webkit-transform: rotate(0deg);
  }
  to {
    -webkit-transform: rotate(360deg);
  }
}
@-webkit-keyframes rotateCC {
  from {
    -webkit-transform: rotate(0deg);
  }
  to {
    -webkit-transform: rotate(-360deg);
  }
}

#welcomeWave{
	position:absolute;
}
#welcomeInner{
	position:absolute;
	-webkit-animation-name:             rotate;
	-webkit-animation-duration:         5s;
	-webkit-animation-iteration-count:  infinite;
	-webkit-animation-timing-function: linear;
	pointer-events:none;
}
#welcomeOuter{
	position:absolute;
        -webkit-animation-name:             rotateCC;
        -webkit-animation-duration:         5s;
        -webkit-animation-iteration-count:  infinite;
        -webkit-animation-timing-function: linear;
	pointer-events:none;
}
		</style>
	</head>
	<body style="background-color:#242424;">
		<div id="container">
			<div id="welcomeWave">
				<img src="images/welcome_wave.png" id="wave">
			</div>
			<div id="welcomeInner">
				<img src="images/welcome_inner.png" id="inner">
			</div>
			<div id="welcomeOuter">
				<img src="images/welcome_outer.png" id="outer">
			</div>
		</div>
	</body>
</html>
