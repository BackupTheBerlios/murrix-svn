<?

require_once("config.inc.php");

require_once("classes/class.mvar.php");
require_once("classes/class.mobject.php");
require_once("classes/class.mthumbnail.php");
require_once("classes/class.script.php");

require_once("3dparty/exifer/exif.php");

require_once("system/functions.php");
require_once("system/design.php");
require_once("system/system.php");

require_once("vars.php");
require_once("session.php");

$_SESSION['murrix']['site'] = "standard";

if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";

if (!isset($_GET['path']))
	$path = "/Root";
else
	$path = $_GET['path'];

$object = new mObject(resolvePath($path));

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
		<?
		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\"/>\n";

		?>
	</head>
	
	<body>
		<div id="main">
			<div id="content">
			<?
				$left = img(geticon($object->getIcon()))." ".$object->getName();
				$right = $object->getNodeId();
				$center = "";
				include(gettpl("big_title"));
				?>
				<div class="main">
					<form action="<?=$_SERVER["SCRIPT_NAME"]?>" method="get" name="resolveForm">
						<input class="hidden" type="hidden" name="input_id" value="<?=$_GET['input_id']?>"/>
						<input class="hidden" type="hidden" name="form_id" value="<?=$_GET['form_id']?>"/>
						<input class="form" name="path" type="text" value="<?=$path?>"/>
						<input class="submit" name="submit" type="submit" value="<?=ucf(i18n("resolve"))?>"/>
					</form>
				</div>
				<?
				$node_id = $object->getNodeId();

				$parent_path = GetParentPath($object->getPath());
				$parent_id = resolvePath($parent_path);

				if ($parent_id > 0 && $parent_id != $node_id)
				{
					$parent = new mObject($parent_id);
					?>
					<div class="main">
					<?
						echo "<a href=\"".$_SERVER["SCRIPT_NAME"]."?input_id=".$_GET['input_id']."&form_id=".$_GET['form_id']."&path=$parent_path\">".img(geticon($parent->getIcon()))." <strong>".ucf(i18n("up one level"))."</strong></a>";
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
							echo "<a href=\"".$_SERVER["SCRIPT_NAME"]."?input_id=".$_GET['input_id']."&form_id=".$_GET['form_id']."&path=".$child->getPath()."\">".img(geticon($child->getIcon()))." ".$child->getName()."</a>";
						?>
						</div>
					<?
					}
				}
				?>
				<center>
					<input type="button" class="submit" onclick="opener.document.getElementById('<?=$_GET['form_id']?>').<?=$_GET['input_id']?>.value = '<?=$object->getNodeId()?>';parent.window.close();" value="<?=ucf(i18n("select"))?>"/>
				</center>
			</div>
		</div>
	</body>
</html>