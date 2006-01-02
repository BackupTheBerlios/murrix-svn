<?
require_once("vars.php");

require_once("system/functions.php");
require_once("system/design.php");
require_once("system/system.php");

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

$_SESSION['murrix']['languages'] = $site_config['sites'][$_SESSION['murrix']['site']]['languages'];

if (!isset($_SESSION['murrix']['language']))
	$_SESSION['murrix']['language'] = $_SESSION['murrix']['languages'][0];

if (!isset($_SESSION['murrix']['user']))
	$_SESSION['murrix']['user'] = new mObject($anonymous_id);

CompileRights();

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
	$filename = $file->getVarValue("file");

	if (!isset($_GET['maxwidth']) && !isset($_GET['angle']))
		@readfile($filename);
	else
	{
		$quality = 100;
		//if ($item->type == ALBUMITEM_TYPE_PICTURE)
		{
			$image = imagecreatefromjpeg($filename);
			$angle = $_GET['angle'];
			
			if (!empty($angle))
			{
				$angle = $angle % 360;
				if ($angle > 0)
				{
					$out = ImageRotate($image, $angle, 180);
					imagedestroy($image);
					$image = $out;
				}
			}
			
			if (isset($_GET['maxwidth']) && $_GET['maxwidth'] < imagesx($image))
			{
				$outputw = $_GET['maxwidth'];
				$outputh = imagesy($image) * ($_GET['maxwidth'] / imagesx($image));
				
				$output = imagecreatetruecolor($outputw, $outputh);
				
				imagecopyresampled($output, $image, 0, 0, 0, 0, imagesx($output), imagesy($output), imagesx($image), imagesy($image));
				
				imagedestroy($image);
				imagejpeg($output, '', $quality);
				imagedestroy($output);
			}
			else
			{
				imagejpeg($image, '', $quality);
				imagedestroy($image);
			}
		}
	}
	return;
}

include(gettpl("pagelayout"));

?>