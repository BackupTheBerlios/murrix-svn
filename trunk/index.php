<?
include_once("profiler.inc.php");
$prof = new Profiler(true, true);

$prof->startTimer( "include" );
/* ========================= */
// Load pathvars etc.
/* ========================= */
require_once("vars.php");


/* ========================= */
// Load basic functions
/* ========================= */
require_once("system/functions.php");
require_once("system/design.php");
require_once("system/system.php");
require_once("system/fetch.php");
require_once("system/paths.php");
require_once("system/filecache.php");
require_once("system/objectcache.php");


/* ========================= */
// Se if we should run install
/* ========================= */
if (!file_exists("config.inc.php"))
{
	header("Location: $wwwpath/install.php");
	exit;
}


/* ========================= */
// Load configuration
/* ========================= */
require_once("config.inc.php");


/* ========================= */
// Load System classes
/* ========================= */
require_once("classes/class.mvar.php");
require_once("classes/class.mobject.php");
require_once("classes/class.mthumbnail.php");
require_once("classes/class.script.php");
require_once("classes/class.calendar.php");


/* ========================= */
// Load 3d-party files
/* ========================= */
require_once("3dparty/exifer/exif.php");

/* ========================= */
// Load themefiles
/* ========================= */
$folders = GetSubfolders("$abspath/design");
foreach ($folders as $folder)
	require_once("$abspath/design/$folder/theme.php");


/* ========================= */
// Set available linktyes
/* ========================= */
if (!isset($link_types))
	$link_types = array("sub" => "child");
else if (!isset($link_types['sub']))
	$link_types = array_merge($link_types, array("sub" => "child"));


/* ========================= */
// Load scriptfiles
/* ========================= */
$folders = GetSubfolders("$abspath/scripts");
foreach ($folders as $folder)
	require_once("$abspath/scripts/$folder/script.php");


/* ========================= */
// Start session
/* ========================= */
require_once("session.php");


/* ========================= */
// Set up system vars!
/* ========================= */
$site = GetInput("site", $default_theme);

$_SESSION['murrix']['site'] = $site;
$_SESSION['murrix']['default_path'] = $site_config['sites'][$site]['start'];

if (empty($_SESSION['murrix']['path']))
	$_SESSION['murrix']['path'] = $_SESSION['murrix']['default_path'];


/* ========================= */
// Load translations
/* ========================= */
$files = GetSubfiles("$abspath/design/$site/translations");
foreach ($files as $file)
{
	$parts = SplitFilepath($file);
	include_once("$abspath/design/$site/translations/$file");
	$_SESSION['murrix']['translations'][$parts['name']] = $translation;
}

/* ========================= */
// Load theme includes
/* ========================= */
$files = GetSubfiles("$abspath/design/$site/include");
foreach ($files as $file)
{
	$parts = SplitFilepath($file);
	include_once("$abspath/design/$site/include/$file");
}
$prof->stopTimer( "include" );
$prof->startTimer( "database" );
/* ========================= */
// Connect to database
/* ========================= */
if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";
$prof->stopTimer( "database" );

/* ========================= */
// Clear cache
/* ========================= */
$_SESSION['murrix']['callcache'] = array();


/* ========================= */
// Define available languages
/* ========================= */
$_SESSION['murrix']['languages'] = $site_config['sites'][$site]['languages'];


/* ========================= */
// Set default language
/* ========================= */
if (!isset($_SESSION['murrix']['language']))
	$_SESSION['murrix']['language'] = $_SESSION['murrix']['languages'][0];


/* ========================= */
// Load anonymous user
/* ========================= */
if (!isset($_SESSION['murrix']['user']))
	$_SESSION['murrix']['user'] = new mObject($anonymous_id);

$prof->startTimer( "system" );
/* ========================= */
// Init system
/* ========================= */
if (!isset($_SESSION['murrix']['System']))
	$_SESSION['murrix']['System'] = new mSystem(isset($ajax_path) ? $ajax_path : "");
	
$_SESSION['murrix']['System']->LoadScripts();
$prof->stopTimer( "system" );

/* ========================= */
// Process ajax-calls
if (isset($_GET['debug']))
	$_SESSION['murrix']['System']->xajax->debugOn();
else
	$_SESSION['murrix']['System']->xajax->debugOff();
$_SESSION['murrix']['System']->Process();


/* ========================= */
// Handle files and thumbnails
/* ========================= */
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
		case "pdf":
			header("Content-type: application/pdf");
			if (!isset($_GET['download']))
				header("Content-Disposition: inline; filename=\"".$file->getVarValue("file", true)."\"");
			break;
		}
		
		@readfile($filename);
	}
	else
		echo "No rights";
	
	return;
}

//$prof->startTimer( "pagelayout" );

include(gettpl("pagelayout"));

//$prof->stopTimer( "pagelayout" );

$_SESSION['murrix']['callcache'] = array();
$_SESSION['murrix']['querycache'] = array();

if (isset($_GET['debug']))
	$prof->printTimers( true );

?>