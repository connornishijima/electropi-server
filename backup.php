<?php
	include("password_protect.php");
	$title = "BACKUPS";
	$hideSettings = True;
	$import = "0";

	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	$updated = "False";

	if($_GET["export"] == "true"){
		file_put_contents("misc/export.state","1");
		die("EXPORTING!");
	}

	if($_POST["import"] == "true"){
		$import = "1";
		file_put_contents("misc/import.state",$_FILES["fileToUpload"]["name"]);

		$target_dir = "conf/temp/uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		} else {
		    echo "Sorry, there was an error uploading your file.";
		}
	}

	$fileLink = "NO BACKUPS YET!";
	$dir = "conf/temp/";
	$filesList = scandir($dir);
	foreach($filesList as &$file){
		$filename = $file;
		$file = explode(".",$file);
		if($file[1] == "epc"){
			$fileLink = "<a href='conf/temp/".$filename."'>".$filename."</a>";
		}
	}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale;?>,maximum-scale=<?php echo $uiScale;?>, user-scalable=no"/>
		<title>ElectroPi Config</title>

		<?php include("header.php");?>

		<script type="text/javascript" src="js/jscolor.js"></script>
		<script type="text/javascript">
			$(function(){  // $(document).ready shorthand
				$("#notify").hide();
				if(<?php echo json_encode($animations);?> == "ENABLED"){
					$('#subtitle').hide().fadeIn('slow');
					$("#logoText").animate({color: "<?php echo $offColor; ?>" });
				}
				else{
                                        $("#logoText").animate({color: "<?php echo $offColor; ?>" },0);
                                }
				if(<?php echo json_encode($updated);?> == "TRUE"){
					$( "#alert" ).toggle();
				}
				if(<?php echo json_encode($import);?> == "1"){
					var status = document.getElementById("importStatus");
		                        status.innerHTML = "IMPORTING! PLEASE WAIT...";
		                        setInterval(checkIRefresh,500);
				}

    			});

		function doExport(){
			$('#dummy').load("backup.php?export=true");
			var status = document.getElementById("exportStatus");
			status.innerHTML = "EXPORTING! PLEASE WAIT...";
			setInterval(checkERefresh,500);
		}

		function checkERefresh(){
        var ajaxRequestN;  // The variable that makes Ajax possible!

        try{
                // Opera 8.0+, Firefox, Safari
                ajaxRequestN = new XMLHttpRequest();
        } catch (e){
                // Internet Explorer Browsers
        try{
                ajaxRequestN = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
        try{
                ajaxRequestN = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e){
                // Something went wrong
                alert('Your browser broke!');
                return false;
        }
        }
        }
        // Create a function that will receive data sent from the server
        ajaxRequestN.onreadystatechange = function(){
                if(ajaxRequestN.readyState == 4){
                        if(ajaxRequestN.responseText == "0"){
				window.location = "backup.php";
			}
                }
        };
        ajaxRequestN.open('POST', 'misc/export.state', true);
        ajaxRequestN.send(null);

		}

		function checkIRefresh(){
        var ajaxRequestN;  // The variable that makes Ajax possible!

        try{
                // Opera 8.0+, Firefox, Safari
                ajaxRequestN = new XMLHttpRequest();
        } catch (e){
                // Internet Explorer Browsers
        try{
                ajaxRequestN = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
        try{
                ajaxRequestN = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e){
                // Something went wrong
                alert('Your browser broke!');
                return false;
        }
        }
        }
        // Create a function that will receive data sent from the server
        ajaxRequestN.onreadystatechange = function(){
                if(ajaxRequestN.readyState == 4){
                        if(ajaxRequestN.responseText == "0"){
				window.location = "backup.php";
			}
                }
        };
        ajaxRequestN.open('POST', 'misc/import.state', true);
        ajaxRequestN.send(null);

		}

		</script>

	</head>

	<body id="body">

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
		<tr><td><div id="alert">Settings updated. <a href="index.php" style="white-space: nowrap;color: <?php echo $offColor; ?>;">Return to control?</a></div></td></tr>
		</table>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> <a href="setup.php" style="color:<?php echo $offColor; ?>;">CONFIG</a> >> <a href="security.php" style="color:<?php echo $offColor; ?>;">SECURITY</a> >> BACKUP / RESTORE</td></tr>
			<tr id="verticalSpace"></tr>
		</table>
		<br>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #181818;">
			<tr>
				<td><div id="settingHeader">EXPORT</td>
				<td id="exportStatus" style="padding: 10px;text-align:right;">LAST BACKUP: <?php echo $fileLink;?></td>
			</tr>
			<tr>
				<td style="padding: 10px;text-align:left;">Use this to export a *.epc (ElectroPi Configuration) file.</td>
				<td style="padding: 10px;text-align:right;"><input type="submit" value="EXPORT" onclick="doExport();" style="width: 100px;height: 50px;border: none;background-color: <?php echo $offColor; ?>;font-family: 'Oswald', sans-serif;font-size: 20px;margin-bottom: 5px;margin-right: 5px;"></input></td>

			</tr>

		</table>

		<br>

		<form action="backup.php" method="POST" enctype="multipart/form-data">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #181818;">
			<tr>
				<td><div id="settingHeader">IMPORT</td>
				<td id="importStatus" style="text-align:right;"><input type="file" name="fileToUpload" id="fileToUpload" style="width: 190px;float: right;"></td>
			</tr>
			<tr>
				<td style="padding: 10px;">Use this to import a *.epc (ElectroPi Configuration) file.</td>
				<td style="padding: 10px;text-align:right;"><input type="submit" value="IMPORT" style="width: 100px;height: 50px;border: none;background-color: <?php echo $offColor; ?>;font-family: 'Oswald', sans-serif;font-size: 20px;margin-bottom: 5px;margin-right: 5px;"></input></td>
			</tr>

                </table>
		<input type="hidden" name="import" value="true">
                </form>

		<div id="dummy" style="display:none"></div>

		<?php include("footer.php");?>
	</body>
</html>
