<?

require_once("config.inc.php");

require_once("classes/class.mvar.php");
require_once("classes/class.mobject.php");
require_once("classes/class.mthumbnail.php");
require_once("classes/class.script.php");

require_once("3dparty/exifer/exif.php");

require_once("system/functions.php");
require_once("system/design.php");
require_once("system/system.php");

require_once("vars.php");
require_once("session.php");

if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";

$parent = new mObject(resolvePath($_SESSION['murrix']['path']));

if (!$parent->hasRight("create_subnodes", array("file", "file_folder")))
{
	echo "You do not have enough rights to upload files.";
	exit;
}

if (isset($_POST['action']) && $_POST['action'] == "upload")
{
	move_uploaded_file($_FILES['file']['tmp_name'], $_FILES['file']['tmp_name']."_tmpfile");
?>
	<script type="text/javascript">
		opener.document.getElementById("sEdit").<?=$_POST['varid']?>.value = '<?=$_FILES['file']['name']?>' + ':' + '<?=$_FILES['file']['tmp_name']."_tmpfile"?>';
		opener.document.getElementById("sEdit").<?="n".$_POST['varid']?>.value = '<?=$_FILES['file']['name']?>';
		self.close();
	</script>
	<?
	exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>MURRiX File Upload</title>
		<META NAME="ROBOTS" CONTENT="NOINDEX">
		<?
		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\"/>\n";
		?>
	</head>
	
	<body>
		<form action="<?=$_SERVER["SCRIPT_URI"]?>" enctype="multipart/form-data" method="post" name="uploadForm">
			<input class="hidden" type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
			<input class="hidden" type="hidden" name="varid" value="<?=$_GET['varid']?>"/>
			<input class="hidden" name="action" type="hidden" value="upload"/>
			<input class="upload" name="file" type="file"/>
			<br/>
			<input class="submit" name="submit" type="submit" value="Upload"/>
			<input class="submit" name="close" type="button" onClick="parent.window.close()" value="Close"/>
		</form>
	</body>
</html>
