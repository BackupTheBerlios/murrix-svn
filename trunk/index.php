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
require_once("system/settings.php");
require_once("system/user.php");


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
require_once("classes/class.mrss.php");
require_once("classes/class.script.php");
require_once("classes/class.cscript.php");
require_once("classes/class.calendar.php");
require_once("classes/class.mtable.php");
require_once("classes/class.muser.php");
require_once("classes/class.mgroup.php");


/* ========================= */
// Load 3d-party files
/* ========================= */
require_once("3dparty/exifer/exif.php");


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
// Load console scriptfiles
/* ========================= */
$files = GetSubfiles("$abspath/cscripts");
foreach ($files as $file)
	require_once("$abspath/cscripts/$file");

/* ========================= */
// Start session
/* ========================= */
require_once("session.php");



$prof->stopTimer( "include" );
$prof->startTimer( "database" );
/* ========================= */
// Connect to database
/* ========================= */
if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";
$prof->stopTimer( "database" );


/* ========================= */
// Set initial data
/* ========================= */
$root_id = getSetting("ROOT_NODE_ID", 1, "any");
$anonymous_id = getSetting("ANONYMOUS_ID", 1, "any");

$theme = GetInput("theme", getSetting("DEFAULT_THEME", "standard", "any"));

$_SESSION['murrix']['theme'] = $theme;
$_SESSION['murrix']['site'] = $theme;
$_SESSION['murrix']['default_path'] = getSetting("DEFAULT_PATH", "/root/public");

if (empty($_SESSION['murrix']['path']) || $_SESSION['murrix']['path'] == "/")
	$_SESSION['murrix']['path'] = $_SESSION['murrix']['default_path'];


/* ========================= */
// Load theme includes
/* ========================= */
$files = GetSubfiles("$abspath/design/$theme/include");
foreach ($files as $file)
{
	$parts = SplitFilepath($file);
	include_once("$abspath/design/$theme/include/$file");
}


/* ========================= */
// Load translations
/* ========================= */
$files = GetSubfiles("$abspath/design/$theme/translations");
$_SESSION['murrix']['languages'] = array();
foreach ($files as $file)
{
	include_once("$abspath/design/$theme/translations/$file");
	$_SESSION['murrix']['languages'][] = basename($file, ".php");
}

if (!isset($_SESSION['murrix']['language']))
	$_SESSION['murrix']['language'] = getSetting("DEFAULT_LANG", "eng");


/* ========================= */
// Clear cache
/* ========================= */
$_SESSION['murrix']['callcache'] = array();


/* ========================= */
// Load anonymous user
/* ========================= */
if (!isset($_SESSION['murrix']['user']))
	$_SESSION['murrix']['user'] = new mUser($anonymous_id);

$prof->startTimer( "system" );
/* ========================= */
// Init system
/* ========================= */
if (!isset($_SESSION['murrix']['System']))
	$_SESSION['murrix']['System'] = new mSystem(isset($ajax_path) ? $ajax_path : "");
	
//$_SESSION['murrix']['System']->LoadScripts();
$prof->stopTimer( "system" );


/* ========================= */
// Handle files and thumbnails
/* ========================= */
if (isset($_GET['thumbnail']))
{
	if (in_array($_GET['thumbnail'], $_SESSION['murrix']['rightcache']['thumbnail']))
	{
		$thumbnail = new mThumbnail($_GET['thumbnail']);
		$thumbnail->Output();
	}
	else
		echo "No rights";
		
	return;
}
else if (isset($_GET['file']))
{
	$query = "SELECT `data` FROM `".$db_prefix."values` WHERE `id`='".$_GET['file']."'";

	$result = mysql_query($query) or die("index.php: " . mysql_errno() . " " . mysql_error());
	$data = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$filename = $data['data'];
	
	$extension = pathinfo($filename, PATHINFO_EXTENSION);

	if (in_array($_GET['file'], $_SESSION['murrix']['rightcache']['file']))
	{
		if (isset($_GET['download']))
		{
			header("Content-type: application/force-download");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=\"".$data['data']."\"");
		}
	
		$type = getfiletype($extension);

		switch ($type)
		{
		case "image":
			header("Content-type: " . image_type_to_mime_type(IMAGETYPE_JPEG));
			break;
			
		case "pdf":
			header("Content-type: application/pdf");
			if (!isset($_GET['download']))
				header("Content-Disposition: inline; filename=\"".$data['data']."\"");
			break;
		}
		
		@readfile("$abspath/files/".$_GET['file'].".$extension");
	}
	else
		echo "No rights";
	
	return;
}

$_SESSION['murrix']['rightcache']['file'] = array();
$_SESSION['murrix']['rightcache']['thumbnail'] = array();

/* ========================= */
// Process ajax-calls
if (isset($_GET['debug']))
	$_SESSION['murrix']['System']->xajax->debugOn();
else
	$_SESSION['murrix']['System']->xajax->debugOff();

$_SESSION['murrix']['System']->Process();

include(gettpl("pagelayout"));

//$prof->stopTimer( "pagelayout" );

$_SESSION['murrix']['callcache'] = array();
$_SESSION['murrix']['querycache'] = array();

if (isset($_GET['debug']))
	$prof->printTimers( true );

?>