<?
require_once("vars.php");

if (file_exists("config.inc.php"))
{
	echo "A configfile already exists, remove it to enable the installer.<br/>";
	exit;
}
require_once("system/functions.php");
require_once("system/design.php");
require_once("system/system.php");
require_once("system/fetch.php");
require_once("system/paths.php");
require_once("system/objectcache.php");
require_once("system/settings.php");
require_once("system/user.php");

require_once("classes/class.script.php");
require_once("classes/class.mobject.php");
require_once("classes/class.mvar.php");
require_once("classes/class.mthumbnail.php");
require_once("classes/class.mtable.php");

$folders = GetSubfolders("$abspath/scripts");
foreach ($folders as $folder)
	require_once("$abspath/scripts/$folder/script.php");

session_name("MURRiX_Installer");
session_start();

global $db_prefix;

$_SESSION['murrix']['site'] = "murrix";
$_SESSION['murrix']['language'] = "eng";

if (!isset($_SESSION['murrix']['System']))
	$_SESSION['murrix']['System'] = new mSystem($_SERVER['REQUEST_URI']);

$_SESSION['murrix']['System']->xajax->debugOff();
$_SESSION['murrix']['System']->LoadScripts();
$_SESSION['murrix']['System']->Process();

include(gettpl("install/pagelayout"));
?>