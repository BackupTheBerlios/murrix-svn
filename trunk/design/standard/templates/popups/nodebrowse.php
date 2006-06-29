<?

list($abspath) = explode("/design/", getcwd());
$wwwpath = "";

require_once("$abspath/config.inc.php");

require_once("$abspath/classes/class.mvar.php");
require_once("$abspath/classes/class.mobject.php");
require_once("$abspath/classes/class.mthumbnail.php");
require_once("$abspath/classes/class.script.php");
require_once("$abspath/classes/class.mtable.php");
require_once("$abspath/classes/class.mgroup.php");
require_once("$abspath/classes/class.muser.php");

require_once("$abspath/system/functions.php");
require_once("$abspath/system/design.php");
require_once("$abspath/system/fetch.php");
require_once("$abspath/system/paths.php");
require_once("$abspath/system/objectcache.php");
require_once("$abspath/system/settings.php");

require_once("$abspath/session.php");

if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";

$root_id = getSetting("ROOT_NODE_ID", 1, "any");
$anonymous_id = getSetting("ANONYMOUS_ID", 1, "any");

if (!isset($_GET['path']))
	$path = empty($_SESSION['murrix']['node_browse_last']) ? "/root" : $_SESSION['murrix']['node_browse_last'];
else
	$path = urldecode($_GET['path']);
	
$_SESSION['murrix']['node_browse_last'] = $path;

$object = new mObject(getNode($path));

if (!$object->hasRight("read"))
{
	echo "You do not have enough rights";
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>MURRiX <?=ucf(i18n("browse"))?></title>
		<meta name="robots" content="noindex"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
		
		<link rel="shortcut icon" href="<?=geticon("murrix")?>" type="image/x-icon"/>
			
		<link rel="stylesheet" type="text/css" href="<?="$wwwpath/design/".$_SESSION['murrix']['theme']."/stylesheets/nodebrowse.css"?>"/>
	</head>
	
	<body class="nodebrowse">
		<?
			$left = img(geticon($object->getIcon()))." ".$object->getName();
			$right = $object->getNodeId();
			$center = "";
			include(gettpl("big_title"));
			?>
			<div class="main">
				<form action="<?=$_SERVER["SCRIPT_NAME"]?>" method="get" name="resolveForm">
					<?
					if (isset($_GET['input_id']))
					{
						?><input class="hidden" type="hidden" name="input_id" value="<?=$_GET['input_id']?>"/><?
					}

					if (isset($_GET['input_path_id']))
					{
						?><input class="hidden" type="hidden" name="input_path_id" value="<?=$_GET['input_path_id']?>"/><?
					}
					?>
					<input class="hidden" type="hidden" name="form_id" value="<?=$_GET['form_id']?>"/>
					<input class="input" name="path" type="text" value="<?=urldecode($path)?>"/>
					<input class="submit" name="submit" type="submit" value="<?=ucf(i18n("resolve"))?>"/>
				</form>
			</div>
			<?
			$node_id = $object->getNodeId();

			$parent_path = GetParentPath($object->getPath());
			$parent_id = getNode($parent_path);

			if ($parent_id > 0 && $parent_id != $node_id)
			{
				$parent = new mObject($parent_id);
				?>
				<div class="main">
				<?
					echo "<a href=\"".$_SERVER["REQUEST_URI"]."&path=".urlencode($parent_path)."\">".img(geticon($parent->getIcon()))." <strong>".ucf(i18n("up one level"))."</strong></a>";
				?>
				</div>
			<?
			}

			$children = fetch("FETCH node WHERE link:node_top='$node_id' AND link:type='sub' NODESORTBY property:version SORTBY property:name");

			if (count($children) > 0)
			{
				foreach ($children as $child)
				{
				?>
					<div class="main">
					<?
						echo "<a href=\"".$_SERVER["REQUEST_URI"]."&path=".urlencode($child->getPath())."\">".img(geticon($child->getIcon()))." ".$child->getName()."</a>";
					?>
					</div>
				<?
				}
			}
			?>
			<center>
			<?
				$js = "";
				if (isset($_GET['input_id']))
					$js .= "opener.document.getElementById('".$_GET['input_id']."').value='".$object->getNodeId()."';";

				if (isset($_GET['input_path_id']))
					$js .= "opener.document.getElementById('".$_GET['input_path_id']."').value='$path';";
				?>
				<input type="button" class="submit" onclick="<?=$js?>;parent.window.close();" value="<?=ucf(i18n("select"))?>"/>
			</center>
	</body>
</html>