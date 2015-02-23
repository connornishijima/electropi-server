<?php

$lockColor = $sets["SETTINGS"]["offColor"];

$passMD5 = trim(file_get_contents("../local/epi_pass_md5.txt"));

if($passMD5 == ""){
	file_put_contents("../local/epi_pass_md5.txt","4dfcb7e47d53ff431f231f8bfc51c32d");
}

if(isset($_GET["oldPass"])){
	$oldPass = md5($_GET["oldPass"]);
}
if(isset($_GET["newPass"])){
	$newPass = md5($_GET["newPass"]);
	if($oldPass == $passMD5){
		file_put_contents("../local/epi_pass_md5.txt",$newPass);
	}
}

###############################################################
# Page Password Protect 2.13
###############################################################
# Visit http://www.zubrag.com/scripts/ for updates
############################################################### 
#
# Usage:
# Set usernames / passwords below between SETTINGS START and SETTINGS END.
# Open it in browser with "help" parameter to get the code
# to add to all files being protected. 
#    Example: password_protect.php?help
# Include protection string which it gave you into every file that needs to be protected
#
# Add following HTML code to your page where you want to have logout link
# <a href="http://www.example.com/path/to/protected/page.php?logout=1">Logout</a>
#
###############################################################

/*
-------------------------------------------------------------------
SAMPLE if you only want to request login and password on login form.
Each row represents different user.

$LOGIN_INFORMATION = array(
  'zubrag' => 'root',
  'test' => 'testpass',
  'admin' => 'passwd'
);

--------------------------------------------------------------------
SAMPLE if you only want to request only password on login form.
Note: only passwords are listed

$LOGIN_INFORMATION = array(
  'root',
  'testpass',
  'passwd'
);

--------------------------------------------------------------------
*/

##################################################################
#  SETTINGS START
##################################################################

// Add login/password pairs below, like described above
// NOTE: all rows except last must have comma "," at the end of line
$LOGIN_INFORMATION = array(
  $passMD5
);

// request login? true - show login and password boxes, false - password box only
define('USE_USERNAME', false);

// User will be redirected to this page after logout
define('LOGOUT_URL', 'index.php');

// time out after NN minutes of inactivity. Set to 0 to not timeout
define('TIMEOUT_MINUTES', 0);

// This parameter is only useful when TIMEOUT_MINUTES is not zero
// true - timeout time from last activity, false - timeout time from login
define('TIMEOUT_CHECK_ACTIVITY', true);

##################################################################
#  SETTINGS END
##################################################################

///////////////////////////////////////////////////////
// do not change code below
///////////////////////////////////////////////////////

// show usage example
if(isset($_GET['help'])) {
  die('Include following code into every page you would like to protect, at the very beginning (first line):<br>&lt;?php include("' . str_replace('\\','\\\\',__FILE__) . '"); ?&gt;');
}

// timeout in seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

// logout?
if(isset($_GET['logout'])) {
  setcookie("verify", '', $timeout, '/'); // clear password;
  header('Location: ' . LOGOUT_URL);
  exit();
}

if(!function_exists('showLoginPasswordProtect')) {

// show login form
function showLoginPasswordProtect($error_msg) {

$passMD5 = trim(file_get_contents("../local/epi_pass_md5.txt"));

if(trim($passMD5) == "4dfcb7e47d53ff431f231f8bfc51c32d"){
	$error_msg = "Password is default 'electropi',<br>please change this immediately!";
}

?>
<html>
<head>
  <title>ENTER PASSWORD</title>
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
  <meta name="viewport" content="width=device-width, initial-scale=1.0>,maximum-scale=1.0, user-scalable=no"/>

	<link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>

  <STYLE>
	body{
		background-color: #181818;
		color: #aaa;
		font-family: 'Oswald',sans-serif;
		padding-top: 20px;
	}
	h3{
		font-weight: lighter;
		font-size: 36px;
		margin-bottom: 0px;
		padding-top: 20px;

	}
	h4{
		padding-top: 40px;
		margin: 0px 0px;
		font-size: 18px;
		font-family: 'Dosis',sans-serif;
	}
	#passWord{
		margin-top: -10px;
		padding: 10px;
		text-align: center;
		background-color: #080808;
		color: #cccccc;
		font-family: 'Dosis',sans-serif;
		font-size: 24px;
		width:100%;
	}
	#submit{
		font-size: 20px;
		font-family: 'Oswald',sans-serif;
		padding: 10px;
		width: 200px;
		background-color: #666666;
	}
	#lock{
		width: 128px;
		margin-left: auto;
		margin-right: auto;
		margin-bottom: -25px;
	}
	#error{
		color: #ff0000;
		margin: 0px;
		margin-top: 10px;
        }

  </STYLE>
</head>
<body>
  <style>
    input { border: 1px solid black; }
  </style>
  <div style="width:300px; margin-left:auto; margin-right:auto; text-align:center">
    <div id="lock">
      <img src="images/lock.png">
    </div>
  </div>
  <div style="width:200px; margin-left:auto; margin-right:auto; text-align:center">
  <form method="post">
    <h4>ElectroPi requires a password for control!</h4>
    <div id="error"><?php echo $error_msg; ?></div><br />
<?php if (USE_USERNAME) echo 'Login:<br /><input type="input" name="access_login" /><br />Password:<br />'; ?>
    <input type="password" name="access_password" id="passWord"/><p></p><input type="submit" name="Submit" id="submit" value="Submit" />
  </form>
  <br />
  <a style="font-size:9px; color: #B0B0B0; font-family: Verdana, Arial;" href="http://www.zubrag.com/scripts/password-protect.php" title="Download Password Protector">Powered by Password Protect</a>
  </div>
</body>
</html>

<?php
  // stop at this point
  die();
}
}

// user provided password
if (isset($_POST['access_password'])) {
  $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
  $pass = md5(trim($_POST['access_password']));
  if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
  || (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != trim($pass) ) ) 
  ) {
    showLoginPasswordProtect("Incorrect password.");
  }
  else {
    // set cookie if password was validated
    setcookie("verify", md5($login.'%'.$pass), $timeout, '/');

    // Some programs (like Form1 Bilder) check $_POST array to see if parameters passed
    // So need to clear password protector variables
    unset($_POST['access_login']);
    unset($_POST['access_password']);
    unset($_POST['Submit']);
  }

}

else {

  // check if password cookie is set
  if (!isset($_COOKIE['verify'])) {
    showLoginPasswordProtect("");
  }

  // check if cookie is good
  $found = false;
  foreach($LOGIN_INFORMATION as $key=>$val) {
    $lp = (USE_USERNAME ? $key : '') .'%'.$val;
    if ($_COOKIE['verify'] == md5($lp)) {
      $found = true;
      // prolong timeout
      if (TIMEOUT_CHECK_ACTIVITY) {
        setcookie("verify", md5($lp), $timeout, '/');
      }
      break;
    }
  }
  if (!$found) {
    showLoginPasswordProtect("");
  }

}

?>

