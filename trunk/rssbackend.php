<?
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
// Load System classes
/* ========================= */
require_once("classes/class.mvar.php");
require_once("classes/class.mobject.php");
require_once("classes/class.mthumbnail.php");
require_once("classes/class.script.php");
require_once("classes/class.calendar.php");
require_once("classes/class.mrss.php");

require_once("config.inc.php");

/* ========================= */
// Load 3d-party files
/* ========================= */
require_once("3dparty/exifer/exif.php");


/* ========================= */
// Start session
/* ========================= */
require_once("session.php");


/* ========================= */
// Connect to database
/* ========================= */
if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";

/* ========================= */
// Clear cache
/* ========================= */
$_SESSION['murrix']['callcache'] = array();


/* ========================= */
// Load anonymous user
/* ========================= */
if (!isset($_SESSION['murrix']['user']))
	$_SESSION['murrix']['user'] = new mObject($anonymous_id);


$_SESSION['murrix']['site'] = "standard";

// Include XML_Serializer
require_once 'XML/Serializer.php';

$rss = new mRSS();

if (!isset($_GET['id']))
{
	if (isset($_GET['node_id']))
	{
		$object = new mObject($_GET['node_id']);
		
		echo compiletpl("rsslist", $object);
	}
	else
	{
		$list = $rss->getFeeds();
	
		echo compiletpl("rsslist", $list);
	}
}
else
{
	$rss->outputFeed($_GET['id']);
}

$_SESSION['murrix']['callcache'] = array();
$_SESSION['murrix']['querycache'] = array();



?>


