<?

$abspath = getcwd();

require_once("config.inc.php");
require_once("system/functions.php");
require_once("system/design.php");

session_name("MURRIX");
session_start();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>MURRiX Richtext Editor</title>
		<?
		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\"/>\n";
		?>
		<script type="text/javascript" src="3dparty/rte/richtext.js"></script>
		<script type="text/javascript" src="3dparty/rte/html2xhtml.js"></script>
	</head>
	
	<body>
		<form name="rteform" action="./" method="post" onsubmit="return submitForm();">
			<script type="text/javascript">
				function submitForm()
				{
					updateRTEs();
					opener.document.getElementById("<?=$_GET['formname']?>").<?=$_GET['varid']?>.value = document.rteform.rte.value;
					self.close();
					//change the following line to true to submit form
					return false;
				}

				//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML)
				initRTE("3dparty/rte/images/", "3dparty/rte/", "3dparty/rte/", true);
			</script>

			<script type="text/javascript">
				//Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
				writeRichText('rte', opener.document.getElementById("<?=$_GET['formname']?>").<?=$_GET['varid']?>.value, 600, 300, true, false);
			</script>
			<input class="submit" name="submit" type="submit" value="Save"/>
		</form>
	</body>
</html>
