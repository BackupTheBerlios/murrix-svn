<?

$abspath = "../";
$wwwpath = "";

require_once("$abspath/config.inc.php");

require_once("$abspath/classes/class.mvar.php");
require_once("$abspath/classes/class.mobject.php");
require_once("$abspath/classes/class.mthumbnail.php");
require_once("$abspath/classes/class.script.php");
require_once("$abspath/classes/class.mtable.php");
require_once("$abspath/classes/class.muser.php");
require_once("$abspath/classes/class.mgroup.php");

require_once("$abspath/system/functions.php");
require_once("$abspath/system/design.php");
require_once("$abspath/system/fetch.php");
require_once("$abspath/system/paths.php");
require_once("$abspath/system/filecache.php");
require_once("$abspath/system/objectcache.php");
require_once("$abspath/system/settings.php");
require_once("$abspath/system/user.php");

session_id($_GET['PHPSESSID']);

require_once("$abspath/session.php");

if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";

$root_id = getSetting("ROOT_NODE_ID", 1, "any");
$anonymous_id = getSetting("ANONYMOUS_ID", 1, "any");

function printToLog($text)
{
	echo utf8e($text);
	flush();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>MURRiX File Upload</title>
		<META NAME="ROBOTS" CONTENT="NOINDEX">
	</head>
	<body>
		<font size="3">
		<?
			$parent = new mObject($_GET['node_id']);
			
			if ($parent->hasRight("create"))
			{
				clearNodeFileCache($parent->getNodeId());
				printToLog("Staring processing of ".count($_FILES)." uploaded files...<br/>");
	
				$count = 0;
				$size = 0;
				foreach($_FILES as $tagname => $file)
				{
					// get the temporary name (e.g. /tmp/php34634.tmp)
					$tempName = $file['tmp_name'];
					
					// where to save the file?
					$targetFile = $_POST[$tagname . '_relativePath'];
					printToLog("Processing #$count: $targetFile<br/>");
					
					$targetFile = utf8d($targetFile);
		
					$paths = pathinfo($targetFile);
		
					// Create folders
					
					$dir = $paths['dirname'];
		
					if (empty($dir) || $dir == ".")
						$parent_path = $parent->getPathInTree();
					else
						$parent_path = $parent->getPathInTree()."/$dir";
						
					if (getNode($parent_path) <= 0)
					{
						preg_match_all("/([^\/]*)\/?/i", $dir, $atmp);
						$base = $parent->getPathInTree();
						
						foreach ($atmp[0] as $key => $val)
						{
							$val = str_replace("/", "", $val);
						
							if (empty($val))
								break;
						
							$base_new = "$base/$val";
		
							$node_id = getNode($base_new);
							if ($node_id > 0)
							{
								$base = $base_new;
								continue;
							}
		
							$object = new mObject();
							$object->setClassName("file_folder");
							$object->loadVars();
	
											
							// replace '\\' with '/'
							$val = str_replace("\\", "", $val);
							$val = str_replace("/", "", $val);
							$val = str_replace("+", "", $val);
	
				
							$object->name = $val;
							$object->language = $_SESSION['murrix']['language'];
							$object->rights = $parent->getMeta("initial_rights", $parent->getRights());
		
							if ($object->save())
							{
								guessObjectType($object);
								$parent_new = new mObject(getNode($base));
								$object->linkWithNode($parent_new->getNodeId());
								printToLog("Created file_folder ". $object->getPath()."<br/>");
								clearNodeFileCache($parent_new->getNodeId());
							}
							else
							{
								printToLog("Failed to create file_folder ". $object->getPath()."<br/>");
							}
		
							$base = $base_new;
						}
					}
		
					$parent_new = new mObject(getNode($parent_path));
		
					$object = new mObject();
					$object->setClassName("file");
					$object->loadVars();
	
					$name = trim($paths['basename']);
					// replace '\\' with '/'
					$name = str_replace("\\", "", $name);
					$name = str_replace("/", "", $name);
					$name = str_replace("+", "", $name);
	
					$object->name = $name;
					$object->language = $_SESSION['murrix']['language'];
					$object->rights = $parent_new->getMeta("initial_rights", $parent_new->getRights());
		
					$object->setVarValue("file", trim($paths['basename']).":".$tempName);
		
					if ($object->save())
					{
						guessObjectType($object);
						$object->linkWithNode($parent_new->getNodeId());
						clearNodeFileCache($parent_new->getNodeId());
						printToLog("Created file". $object->getPath()."<br/>");
						$count++;
						$size += $file['size'];
					}
					else
					{
						printToLog("Failed to create file ". $object->getPath()."<br/>");
					}
				}
				
				printToLog("<br/>Successfully processed files<br/>");
				printToLog("$count of ".count($_FILES)." files<br/>");
				printToLog(DownloadSize($size)." total<br/>");
				
			}
			else
				printToLog("<br/>You do not have enough rights to upload files.");
			?>
		</font>
	</body>
</html>