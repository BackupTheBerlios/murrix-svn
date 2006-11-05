<?
require_once("vars.php");

if (file_exists("config.inc.php"))
{
	echo "A configfile already exists, remove it to enable the installer.";
	exit;
}

require_once("XML/Serializer.php");
require_once("XML/Unserializer.php");

require_once("system/functions.php");
require_once("system/design.php");
require_once("system/system.php");
require_once("system/fetch.php");
require_once("system/paths.php");
require_once("system/objectcache.php");
require_once("system/settings.php");
require_once("system/user.php");
require_once("system/class.php");

require_once("classes/class.script.php");
require_once("classes/class.mobject.php");
require_once("classes/class.mvar.php");
require_once("classes/class.mthumbnail.php");
require_once("classes/class.mtable.php");
require_once("classes/class.muser.php");
require_once("classes/class.mgroup.php");
require_once("classes/class.mxml.php");
require_once("classes/class.mmsg.php");

require_once("scripts/install/script.php");

session_name("MURRiX_Installer");
session_start();

global $db_prefix;

$_SESSION['murrix']['theme'] = "install";
$_SESSION['murrix']['language'] = "eng";

if (!isset($_SESSION['murrix']['system']))
{
	$_SESSION['murrix']['system'] = new mSystem();
	$_SESSION['murrix']['system']->loadScript("install");
	$_SESSION['murrix']['system']->transport = "standard";
}

if (!isset($_SESSION['murrix']['user']))
	$_SESSION['murrix']['user'] = new mUser();

$_SESSION['murrix']['system']->process();

include(gettpl("pagelayout"));
?>