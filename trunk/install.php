<?
require_once("vars.php");

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
	$_SESSION['murrix']['System'] = new mSystem($_SERVER['REQUEST_URI']."install.php");

$_SESSION['murrix']['System']->LoadScripts();
$_SESSION['murrix']['System']->Process();

include(gettpl("install/pagelayout"));
?>