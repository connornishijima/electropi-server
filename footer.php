
<html>
	<head>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	</head>
	<body>
		<div id="footerSpace"></div>
		<div id="notify" style="position: fixed;bottom: 0px;width: 100%;text-align: center;">
			<div id="notification"></div>
		</div>
		<div class="push"></div>
		</div>
		<div id="footer">SERVER: <div id="sstatus">LOADING... </div>&nbsp;|&nbsp;
			WATCHDOG: <div id="wstatus">LOADING...</div>&nbsp;|&nbsp;
			CPU: <div id="cstatus">LOADING...</div>&nbsp;|&nbsp;
			<font style="color:#666666;">VERSION: <a href="change.log" style="color:#999999;"><?php echo file_get_contents("conf/local.version");?></a></font>&nbsp;|&nbsp;
			DEVELOPED BY <a href="http://facebook.com/tobifilmgroup">CONNOR NISHIJIMA</a> 2013-2014
		<div>
	</body>
</html>
