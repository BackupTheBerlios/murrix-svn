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

$abspath = getcwd();
$wwwpath = GetParentPath($_SERVER['REQUEST_URI']);

session_id($_GET['PHPSESSID']);
session_name("MURRiX");
session_start();

if (($str = db_connect()) !== true)
	echo "Failed to connect to database!";

?>
<html>
	<body bgcolor="white">
	<?
		$path = $_SESSION['murrix']['path'];
		//echo $_SESSION['murrix']['path'];
		//print_r($_FILES);


		echo "Staring processing of uploaded files...<br/>";flush();
		foreach($_FILES as $tagname => $object)
		{
			// get the temporary name (e.g. /tmp/php34634.tmp)
			$tempName = $object['tmp_name'];
			
			// where to save the file?
			$targetFile = $_POST[$tagname . '_relativePath'];
			echo "Processing $targetFile<br/>";flush();
			// replace '\\' with '/'
			$targetFile = str_replace("\\", "/", $targetFile);
			$targetFile = str_replace("//", "/", $targetFile);

			$paths = pathinfo($targetFile);

			// Create folders
			
			$dir = $paths['dirname'];
			//echo "<br>DIR:$dir<br>";

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
		
					$object->name = $val;
					$object->language = $_SESSION['murrix']['language'];

					if ($object->save())
					{
						//echo "<br>base:$base<br>";
						//echo resolvePath($base)."<br>";
					
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

			$object->name = trim($paths['basename']);
			$object->language = $_SESSION['murrix']['language'];

			$object->setVarValue("file", $object->name.":".$tempName);


			$thumbnail = new mThumbnail();

			$angle = GetFileAngle($tempName);

			$maxsize = 150;
			if ($thumbnail->CreateFromFile($tempName, $paths['extension'], $maxsize, $maxsize, $angle))
			{
				if (!$thumbnail->Save())
					echo "Failed to create thumbnail<br>";

				$object->setVarValue("thumbnail_id", $thumbnail->id);
			}

			
/*			$vars = $object->getVars();

			foreach ($vars as $var)
			{
				$key = "v".$var->id;
				$object->setVarValue($var->name, isset($args[$key]) ? $args[$key] : (isset($args[$var->id]) ? $args[$var->id] : ""));
			}
*/
			if ($object->save())
			{
				//echo $parent_path."<br>";
				//echo resolvePath($parent_path)."<br>";
				$parent = new mObject(resolvePath($parent_path));
				$object->linkWithNode($parent->getNodeId());
				echo "Created file". $object->getPath()."<br/>";flush();
			}
			else
			{
				echo "Failed to create file ". $object->getPath()."<br/>";flush();
			}
		}
		echo "Done processing of uploaded files!";flush();
	?>
	</body>
</html>