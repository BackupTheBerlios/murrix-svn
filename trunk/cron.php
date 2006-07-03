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
require_once("system/settings.php");


/* ========================= */
// Load System classes
/* ========================= */
require_once("classes/class.mvar.php");
require_once("classes/class.mobject.php");
require_once("classes/class.mthumbnail.php");
require_once("classes/class.script.php");
require_once("classes/class.calendar.php");
require_once("classes/class.mxml.php");
require_once("classes/class.mtable.php");

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

echo "MURRiX Cron Script\n\n";flush();


$files = fetch("FETCH node WHERE property:class_name='file_folder' NODESORTBY property:version");
echo "Setting default metadata for file_folders\n";flush();
echo count($files)." file_folders found\n";flush();

foreach ($files as $file)
{
	$file->setMeta("children_show_num_per_page", "all");
	$file->setMeta("view", "thumbnailes");
	
}
echo "\n";flush();

$files = fetch("FETCH node WHERE property:class_name='file' NODESORTBY property:version");
echo "Setting default metadata for files\n";flush();
echo count($files)." files found\n";flush();

foreach ($files as $file)
{
	$file->setMeta("comment_show_num_per_page", "all");
	$file->setMeta("show_comments", 1);
	
}
echo "\n";flush();


echo "Creating thumbnail for default sizes for all image files\n";flush();

$count_small = 0;
$count_big = 0;

foreach ($files as $file)
{
	$value_id = $file->resolveVarName("file");
	$filename = $file->getVarValue("file");
	$type = getfiletype(pathinfo($filename, PATHINFO_EXTENSION));
	
	$data = "";
	if ($type == "image")
	{
		$maxsize_big = getSetting("IMGSIZE", 640);
		$maxsize_small = getSetting("THUMBSIZE", 150);
		
		$angle = $file->getMeta("angle", "");
		
		if (!checkThumbnailExists($value_id, $maxsize_big, 0))
		{
			$start_time = time();
			getThumbnail($value_id, $maxsize_big, 0, $angle);
			$time = time()-$start_time;
			$count_big++;
			echo "Successfully created thumbnail for $filename (Size $maxsize_big) Time: $time second(s)\n";flush();
		}
			
		if (!checkThumbnailExists($value_id, $maxsize_small, $maxsize_small))
		{
			$start_time = time();
			getThumbnail($value_id, $maxsize_small, $maxsize_small, $angle);
			$time = time()-$start_time;
			$count_small++;
			echo "Successfully created thumbnail for $filename (NodeID ".$file->getNodeId().") (Size $maxsize_small) Time: $time second(s)\n";flush();
		}
	}
}

echo "Created thumnails: Size $maxsize_big - $count_big, Size $maxsize_small - $count_small\n";flush();
echo "\n";flush();


echo "Running optimize on database\n";flush();

$result = mysql_list_tables($mysql_db);
for ($i = 0; $i < mysql_num_rows($result); $i++)
{
	$name = mysql_tablename($result, $i);
	$res2 = mysql_query("OPTIMIZE TABLE `$name`");
	$return = mysql_fetch_array($res2, MYSQL_ASSOC);
	echo "Optimizing $name - ".$return['Msg_text']."\n";flush();
}
echo "\n";flush();

echo "Script finished\n";flush();

$_SESSION['murrix']['callcache'] = array();
$_SESSION['murrix']['querycache'] = array();

?>