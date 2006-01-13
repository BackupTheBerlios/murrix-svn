<?
require_once("vars.php");

require_once("system/functions.php");
require_once("system/design.php");
require_once("system/system.php");
require_once("system/paths.php");

if (!file_exists("config.inc.php"))
{
	header("Location: $wwwpath/install.php");
	exit;
}
require_once("config.inc.php");

$site_config['default'] = $default_theme;
require_once("classes/class.mvar.php");
require_once("classes/class.mobject.php");
require_once("classes/class.mthumbnail.php");
require_once("classes/class.script.php");
require_once("classes/class.calendar.php");

require_once("3dparty/exifer/exif.php");

$folders = GetSubfolders("$abspath/design");
foreach ($folders as $folder)
	require_once("$abspath/design/$folder/theme.php");

$folders = GetSubfolders("$abspath/scripts");
foreach ($folders as $folder)
	require_once("$abspath/scripts/$folder/script.php");

require_once("session.php");

/*
if (empty($_SESSION['murrix']['site']))
	$_SESSION['murrix']['site'] = $site_config['default'];
else*/
	$_SESSION['murrix']['site'] = GetInput("site", $site_config['default']);

$files = GetSubfiles("$abspath/design/".$_SESSION['murrix']['site']."/translations");
foreach ($files as $file)
{
	$parts = SplitFilepath($file);
	include_once("$abspath/design/".$_SESSION['murrix']['site']."/translations/$file");
	$_SESSION['murrix']['translations'][$parts['name']] = $translation;
}

if (!isset($_SESSION['murrix']['lastcmd'])) $_SESSION['murrix']['lastcmd'] = "";

$files = GetSubfiles("$abspath/design/".$_SESSION['murrix']['site']."/include");
foreach ($files as $file)
{
	$parts = SplitFilepath($file);
	include_once("$abspath/design/".$_SESSION['murrix']['site']."/include/$file");
}
if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";

$_SESSION['murrix']['callcache'] = array();

$_SESSION['murrix']['languages'] = $site_config['sites'][$_SESSION['murrix']['site']]['languages'];

if (!isset($_SESSION['murrix']['language']))
	$_SESSION['murrix']['language'] = $_SESSION['murrix']['languages'][0];

if (!isset($_SESSION['murrix']['user']))
	$_SESSION['murrix']['user'] = new mObject($anonymous_id);

if (!isset($_SESSION['murrix']['System']))
	$_SESSION['murrix']['System'] = new mSystem();

$_SESSION['murrix']['System']->xajax->debugOff();
$_SESSION['murrix']['System']->LoadScripts();
$_SESSION['murrix']['System']->Process();

$_SESSION['murrix']['default_path'] = $site_config['sites'][$_SESSION['murrix']['site']]['start'];
// Set the default path if none is set
if (empty($_SESSION['murrix']['path']))
	$_SESSION['murrix']['path'] = $_SESSION['murrix']['default_path'];


if (isset($_GET['thumbnail']))
{
	$thumbnail = new mThumbnail($_GET['thumbnail']);
	$thumbnail->Output();
	return;
}
else if (isset($_GET['file']))
{
	$file = new mObject($_GET['file']);

	if ($file->hasRight("read"))
	{
		if (isset($_GET['download']))
		{
			header("Content-type: application/force-download");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=\"".$file->getVarValue("file", true)."\"");
		}
	
		$filename = $file->getVarValue("file");

		$pathinfo = pathinfo($filename);
		$type = getfiletype($pathinfo['extension']);

		switch ($type)
		{
		case "image":
			header("Content-type: " . image_type_to_mime_type(IMAGETYPE_JPEG));
			break;
		}
		
		@readfile($filename);
	}
	else
		echo "No rights";
	
	return;
}

include(gettpl("pagelayout"));

$_SESSION['murrix']['callcache'] = array();

?>