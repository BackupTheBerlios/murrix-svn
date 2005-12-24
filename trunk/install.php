<?
require_once("system/functions.php");
require_once("system/design.php");
require_once("system/system.php");

$abspath = getcwd();
$wwwpath = GetParentPath($_SERVER['REQUEST_URI']);

require_once("classes/class.script.php");

$folders = GetSubfolders("$abspath/scripts");
foreach ($folders as $folder)
	require_once("$abspath/scripts/$folder/script.php");

session_name("MURRIX_INSTALL");
session_start();

$_SESSION['murrix']['site'] = "standard";
$_SESSION['murrix']['languages'] = "eng";

if (!isset($_SESSION['murrix']['System']))
	$_SESSION['murrix']['System'] = new mSystem($_SERVER['REQUEST_URI']);

$_SESSION['murrix']['System']->LoadScripts();
$_SESSION['murrix']['System']->Process();

include(gettpl("install/pagelayout"));
?>