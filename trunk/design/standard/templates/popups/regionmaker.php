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
require_once("$abspath/system/filecache.php");
require_once("$abspath/system/settings.php");
require_once("$abspath/system/user.php");

require_once("$abspath/3dparty/exifer/exif.php");

require_once("$abspath/session.php");

if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";

$root_id = getSetting("ROOT_NODE_ID", 1, "any");
$anonymous_id = getSetting("ANONYMOUS_ID", 1, "any");

$node_id = getInput('node_id', 0);
	
$object = new mObject($node_id);

if (!$object->hasRight("write"))
{
	echo "You do not have enough rights";
	exit;
}

if ($_POST['action'] == "newregion")
{
	$region = new mObject();
	$region->setClassName("image_region");
	$region->loadVars();
	
	$object->language = $_SESSION['murrix']['language'];
	$region->name = "ImageRegion";
	$region->setVarValue("image_width", $_POST['image_width']);
	$region->setVarValue("image_height", $_POST['image_height']);
	$region->setVarValue("text", trim($_POST['Textbox']));
	$region->setVarValue("params", $_POST['PMouseX'].",".$_POST['PMouseY'].",".$_POST['UMouseX'].",".$_POST['UMouseY']);
	
	if ($region->save())
	{
		clearNodeFileCache($object->getNodeId());
		$region->linkWithNode($object->getNodeId());
		
		$remote_node_ids = explode(",", $_POST['remote_node_ids']);
		
		foreach ($remote_node_ids as $rnode_id)
			$region->linkWithNode(trim($rnode_id), "data");
			
		echo "Region created successfully";
	}
	else
	{
		$message = "Operation unsuccessfull.<br/>";
		$message .= "Error output:<br/>";
		$message .= $object->getLastError();
		echo $message;
	}

	exit;
}

$value_id = $object->resolveVarName("file");
$filename = $object->getVarValue("file");
$type = getfiletype(pathinfo($filename, PATHINFO_EXTENSION));

$angle = -1;
$maxsize = getSetting("IMGSIZE", 640);

if ($type != "image")
{
	echo "File is not an image";
	exit;
}

$angle = $object->getMeta("angle", "");
			
$thumbnail = getThumbnail($value_id, $maxsize, $maxsize, $angle);

if ($thumbnail == false)
{
	echo "No thumbnail could be created";
	exit;
}

$_SESSION['murrix']['rightcache']['thumbnail'][] = $thumbnail->id;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>MURRiX <?=ucf(i18n("region maker"))?></title>
		<meta name="robots" content="noindex"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
		
		<link rel="shortcut icon" href="<?=geticon("murrix")?>" type="image/x-icon"/>
			
		<link rel="stylesheet" type="text/css" href="<?="$wwwpath/design/".$_SESSION['murrix']['theme']."/stylesheets/regionmaker.css"?>"/>
		
		<script type="text/javascript">
		<!--
			var capturing = false;
		
			var mouseX = 0;
			var mouseY = 0;
			
			window.document.onmousemove = onMouseMove;
			
			function onMouseMove(e)
			{
				if (navigator.appName == "Netscape")
				{
					mouseX = e.pageX;
					mouseY = e.pageY;
				}
				else
				{
					mouseX = event.clientX + document.body.scrollLeft;
					mouseY = event.clientY + document.body.scrollTop;
				}
			
				if (mouseX < 0)
					mouseX = 0;
					
				if (mouseY < 0)
					mouseY = 0;
					
				document.regionForm.MouseX.value = mouseX;
				document.regionForm.MouseY.value = mouseY;
				
				if (capturing)
				{
					document.regionForm.UMouseX.value = mouseX-document.regionForm.PMouseX.value;
					if (document.regionForm.UMouseX.value < 0)
						document.regionForm.UMouseX.value = 0;
						
					document.regionForm.UMouseY.value = mouseY-document.regionForm.PMouseY.value;
					if (document.regionForm.UMouseY.value < 0)
						document.regionForm.UMouseY.value = 0;
				
					updateRegion();
				}
			
				return true;
			}
			
			function updateRegion()
			{
				document.getElementById('region').style.left = document.regionForm.PMouseX.value+'px';
				document.getElementById('region').style.top = document.regionForm.PMouseY.value+'px';
			
				document.getElementById('region').style.width = document.regionForm.UMouseX.value+'px';
				document.getElementById('region').style.height = document.regionForm.UMouseY.value+'px';
				
				document.getElementById('inner_region').innerHTML = document.regionForm.Textbox.value;
			}
			
			function onImgClick()
			{
				if (!capturing)
				{
					document.regionForm.PMouseX.value = mouseX;
					document.regionForm.PMouseY.value = mouseY;
					document.regionForm.UMouseX.value = 0;
					document.regionForm.UMouseY.value = 0;
					document.getElementById('region').style.visibility = 'visible';
					updateRegion();
				}
				capturing = !capturing;
			}
		// -->
		</script>
	</head>
	
	<body class="regionmaker">
		<form name="regionForm" id="regionForm" action="regionmaker.php" method="post">
			<input name="action" class="hidden" type="hidden" value="newregion"/>
			<input name="node_id" class="hidden" type="hidden" value="<?=$object->getNodeId()?>"/>
			
			<input name="image_width" class="hidden" type="hidden" value="<?=$thumbnail->width?>"/>
			<input name="image_height" class="hidden" type="hidden" value="<?=$thumbnail->height?>"/>
			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td width="<?=$thumbnail->width?>">
						<img onClick="onImgClick()" id="id<?=$thumbnail->id?>" style="width: <?=$thumbnail->width?>px; height: <?=$thumbnail->height?>px;" src="/index.php?thumbnail=<?=$thumbnail->id?>"/>
						Text<br/>
						<textarea style="width: <?=($thumbnail->width-2)?>px;" name="Textbox" onchange="updateRegion()" onkeyup="updateRegion()"></textarea>
						Links (node_id,node_id)<br/>
						<input style="width: <?=($thumbnail->width-2)?>px;" name="remote_node_ids" class="input" type="text" value=""/>
						<br/>
						<input class="submit" type="submit" value="<?=ucf(i18n("create region"))?>"/>
					</td>
					<td valign="top" align="center">
						Pos X<br/>
						<input disabled type="text" name="MouseX" value="0" class="small_input"><br/>
						Pos Y<br/>
						<input disabled type="text" name="MouseY" value="0" class="small_input"><br/>
						X<br/>
						<input onchange="updateRegion()" type="text" name="PMouseX" value="0" class="small_input"><br/>
						Y<br/>
						<input onchange="updateRegion()" type="text" name="PMouseY" value="0" class="small_input"><br/>
						Width<br/>
						<input onchange="updateRegion()" type="text" name="UMouseX" value="0" class="small_input"><br/>
						Height<br/>
						<input onchange="updateRegion()" type="text" name="UMouseY" value="0" class="small_input"><br/>
					</td>
				</tr>
			</table>
		</form>
		<div onClick="onImgClick()" id="region" class="image_region" style="visibility: hidden; position: absolute; z-index: 10">
			<div class="border">
				<div id="inner_region" class="text"></div>
			</div>
		</div>
	</body>
</html>