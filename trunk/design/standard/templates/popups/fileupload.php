<?

list($abspath) = explode("/design/", getcwd());

require_once("$abspath/config.inc.php");

require_once("$abspath/classes/class.mvar.php");
require_once("$abspath/classes/class.mobject.php");
require_once("$abspath/classes/class.mthumbnail.php");
require_once("$abspath/classes/class.script.php");
require_once("$abspath/classes/class.mtable.php");
require_once("$abspath/classes/class.mgroup.php");
require_once("$abspath/classes/class.muser.php");

require_once("$abspath/system/functions.php");
require_once("$abspath/system/design.php");
require_once("$abspath/system/fetch.php");
require_once("$abspath/system/paths.php");
require_once("$abspath/system/objectcache.php");
require_once("$abspath/system/settings.php");
require_once("$abspath/system/user.php");

require_once("$abspath/session.php");

if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";

$root_id = getSetting("ROOT_NODE_ID", 1, "any");
$anonymous_id = getSetting("ANONYMOUS_ID", 1, "any");

$parent_id = getInput("parent_id", getNode($_SESSION['murrix']['path']));

$parent = new mObject($parent_id);

if (!$parent->hasRight("create") && !isAdmin())
{
	echo "You do not have enough rights to upload files.";
	exit;
}

$varid = GetInput("varid");

if (isset($_POST['action']) && $_POST['action'] == "upload")
{
	move_uploaded_file($_FILES['file']['tmp_name'], $_FILES['file']['tmp_name']."_tmpfile");
?>
	<script type="text/javascript">
		parent.document.getElementById('<?=$varid?>').value = '<?=$_FILES['file']['name']?>' + ':' + '<?=$_FILES['file']['tmp_name']."_tmpfile"?>';
		parent.document.getElementById('n<?=$varid?>').value = '<?=$_FILES['file']['name']." - ".DownloadSize(filesize($_FILES['file']['tmp_name']."_tmpfile"))?>';
		self.close();
	</script>
	<?
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>MURRiX File Upload</title>
		<META NAME="ROBOTS" CONTENT="NOINDEX">
	</head>
	
	<body style="margin: 0; padding: 0;">
		<form action="fileupload.php" enctype="multipart/form-data" method="post" name="uploadForm">
			<div>
				<input style="margin: 0;display: none;" type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
				<input style="margin: 0;display: none;" type="hidden" name="varid" value="<?=$varid?>"/>
				<input style="margin: 0;display: none;" name="action" type="hidden" value="upload"/>
				<input style="margin: 0;display: none;" name="parent_id" type="hidden" value="<?=$parent->getNodeId()?>"/>
				<input style="margin: 0;" name="file" type="file"/>
				<input style="margin: 0;" name="submit" type="submit" value="Upload"/>
			</div>
		</form>
	</body>
</html>