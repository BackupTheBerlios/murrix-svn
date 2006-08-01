<?
require_once("../vars.php");
require_once("../session.php");

if (in_array($_GET['id'], $_SESSION['murrix']['rightcache']['thumbnail']))
{
	$filename = "$abspath/../thumbnails/".$_GET['id'].".jpg";
	header("Content-type: ".IMAGETYPE_JPEG);
	header("Last-Modified: ".gmdate("D, d M Y H:i:s", strtotime($_GET['created']))." GMT");
	header("Content-Length: ".filesize($filename));
	@readfile($filename);
	
//	$_SESSION['murrix']['rightcache']['thumbnail'] = array_diff($_SESSION['murrix']['rightcache']['thumbnail'], array($_GET['id']));
}
else
	echo "No rights";
?>
