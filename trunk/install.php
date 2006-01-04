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

require_once("classes/class.script.php");

$folders = GetSubfolders("$abspath/scripts");
foreach ($folders as $folder)
	require_once("$abspath/scripts/$folder/script.php");

require_once("session.php");

$_SESSION['murrix']['site'] = "standard";
$_SESSION['murrix']['languages'] = "eng";

if (!isset($_SESSION['murrix']['System']))
	$_SESSION['murrix']['System'] = new mSystem($_SERVER['REQUEST_URI']);

$_SESSION['murrix']['System']->xajax->debugOff();
$_SESSION['murrix']['System']->LoadScripts();
$_SESSION['murrix']['System']->Process();

include(gettpl("install/pagelayout"));
?>