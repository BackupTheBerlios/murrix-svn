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
require_once("system/paths.php");

require_once("vars.php");

session_id($_GET['PHPSESSID']);

require_once("session.php");

if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>MURRiX File Upload</title>
		<META NAME="ROBOTS" CONTENT="NOINDEX">
		<?
		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\"/>\n";
		?>
	</head>
	<?
		$path = $_SESSION['murrix']['path'];
		$parent = new mObject(resolvePath($path));
	
		if ($parent->hasRight("create_subnodes", array("file", "file_folder")))
		{
			echo "Staring processing of uploaded files...<br/>";flush();

			$count = 1;

			foreach($_FILES as $tagname => $object)
			{
				// get the temporary name (e.g. /tmp/php34634.tmp)
				$tempName = $object['tmp_name'];
				
				// where to save the file?
				$targetFile = $_POST[$tagname . '_relativePath'];
				echo "Processing #$count: $targetFile<br/>";flush();
	
				$paths = pathinfo($targetFile);
	
				// Create folders
				
				$dir = $paths['dirname'];
	
				if (empty($dir) || $dir == ".")
					$parent_path = $path;
				else
					$parent_path = "$path/$dir";
	
				if (resolvePath($parent_path) <= 0)
				{
					preg_match_all("/([^\/]*)\/?/i", $dir, $atmp);
					$base = $path;
					
					foreach ($atmp[0] as $key => $val)
					{
						$val = str_replace("/", "", $val);
					
						if (empty($val))
							break;
					
						$base_new = "$base/$val";
	
						$node_id = resolvePath($base_new);
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
	
						if ($object->save())
						{
							$parent = new mObject(resolvePath($base));
							$object->linkWithNode($parent->getNodeId());
							echo "Created file_folder ". $object->getPath()."<br/>";flush();
						}
						else
						{
							echo "Failed to create file_folder ". $object->getPath()."<br/>";flush();
						}
	
						$base = $base_new;
					}
				}
	
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
	
				$object->setVarValue("file", trim($paths['basename']).":".$tempName);
	
				if ($object->save())
				{
					$parent = new mObject(resolvePath($parent_path));
					$object->linkWithNode($parent->getNodeId());
					echo "Created file". $object->getPath()."<br/>";flush();
					$count++;
				}
				else
				{
					echo "Failed to create file ". $object->getPath()."<br/>";flush();
				}
			}
			
			echo "Done processing of uploaded files. ($count/".count($_FILES).")";flush();
		}
		else
			echo "You do not have enough rights to upload files.";flush();
	?>
	</body>
</html>