<?
$abspath = "../";
$wwwpath = "";

/* ========================= */
// Load System classes
/* ========================= */
require_once("$abspath/classes/class.mvar.php");
require_once("$abspath/classes/class.mobject.php");
require_once("$abspath/classes/class.mthumbnail.php");
require_once("$abspath/classes/class.script.php");
require_once("$abspath/classes/class.calendar.php");
require_once("$abspath/classes/class.mrss.php");
require_once("$abspath/classes/class.mtable.php");
require_once("$abspath/classes/class.mgroup.php");
require_once("$abspath/classes/class.muser.php");


/* ========================= */
// Load basic functions
/* ========================= */
require_once("$abspath/system/functions.php");
require_once("$abspath/system/design.php");
require_once("$abspath/system/fetch.php");
require_once("$abspath/system/paths.php");
require_once("$abspath/system/filecache.php");
require_once("$abspath/system/objectcache.php");
require_once("$abspath/system/settings.php");




require_once("$abspath/config.inc.php");


/* ========================= */
// Start session
/* ========================= */
require_once("$abspath/session.php");


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


$root_id = getSetting("ROOT_NODE_ID", 1, "any");
$anonymous_id = getSetting("ANONYMOUS_ID", 1, "any");
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