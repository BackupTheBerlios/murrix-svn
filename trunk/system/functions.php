<?

function ob_get_end()
{
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}

function date_compare($date1, $date2)
{
//int mcal_date_compare ( int a_year, int a_month, int a_day, int b_year, int b_month, int b_day )

	list($year1, $month1, $day1) = explode("-", $date1);
	list($year2, $month2, $day2) = explode("-", $date2);

	if ($year1 < $year2)
		return -1;
	if ($year1 > $year2)
		return 1;

	if ($month1 < $month2)
		return -1;
	if ($month1 > $month2)
		return 1;

	if ($day1 < $day2)
		return -1;
	if ($day1 > $day2)
		return 1;

	return 0;
	//return mcal_date_compare($year1, $month1, $day1, $year2, $month2, $day2);
}

function utf8d($data)
{
	if (is_array($data))
	{
		$new = array();
		foreach ($data as $key => $d)
			$new[$key] = utf8_decode($d);
		return $new;
	}

	return utf8_decode($data);
}

function utf8e($data)
{
	if (is_array($data))
	{
		$new = array();
		foreach ($data as $key => $d)
			$new[$key] = utf8_encode($d);
		return $new;
	}

	return utf8_encode($data);
}

function db_connect()
{
	global $mysql_address, $mysql_user, $mysql_pass, $mysql_db;
	
	if (!@$db_conn = mysql_pconnect($mysql_address, $mysql_user, $mysql_pass))
		return ("Error connecting to database: " . mysql_errno() . " " . mysql_error());
	
	if (!mysql_select_db($mysql_db))
		return ("mysql_select_db: " . mysql_errno() . " " . mysql_error());
		
	return true;
}

function GetParentPath($path)
{
	$paths = explode("/", $path);
	array_pop($paths);
	return implode("/", $paths);
}

function microtime_float()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

function GetRightsRecursive($object)
{
	$parents = fetch("FETCH node WHERE link:node_bottom='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='group' NODESORTBY property:version SORTBY property:name");
	
	$rights = array();
	foreach ($parents as $parent)
	{
		$children = fetch("FETCH node WHERE link:node_top='".$parent->getNodeId()."' AND link:type='sub' AND (property:class_name='right_read' OR property:class_name='right_edit' OR property:class_name='right_delete' OR property:class_name='right_create_subnodes' OR property:class_name='right_read_subnodes') NODESORTBY property:version SORTBY property:name");
		
		foreach ($children as $child)
			$rights[] = $child;
			
		$rights = array_merge($rights, GetRightsRecursive($parent));
	}
	
	return $rights;
}

function CompileRights()
{
	$rights = GetRightsRecursive($_SESSION['murrix']['user']);
	
	unset($_SESSION['murrix']['rights']);

	// deny has precedence over grant
	//PrintPre($rights);
	foreach ($rights as $right)
	{
		$path = $right->getVarValue("path", true);
		$value = $right->getVarValue("setting", true);

		switch ($right->getClassName())
		{
			case "right_read":
				$_SESSION['murrix']['rights'][$value]['read'][] = resolvePath($path);
				break;

			case "right_edit":
				$_SESSION['murrix']['rights'][$value]['edit'][] = resolvePath($path);
				break;

			case "right_delete":
				$_SESSION['murrix']['rights'][$value]['delete'][] = resolvePath($path);
				break;

			case "right_read_subnodes":
				$_SESSION['murrix']['rights'][$value]['read_subnodes'][] = resolvePath($path);
				break;

			case "right_create_subnodes":
				$_SESSION['murrix']['rights']['allow']['create_subnodes'][] = resolvePath($path);
				$_SESSION['murrix']['rights']['allow']['create_subnodes_classes'][] = $right->getVarValue("classes");
				break;
		}
	}
	//PrintPre($_SESSION['murrix']['rights']);
}

function HasRight($action, $path, $create_classes = array())
{
	$object = new mObject();
	$object->SetByPath($path);

	if ($action == "show")
		$action = "read";
	else if ($action == "new")
		$action = "create";
	else if ($action == "save" || $action == "link" || $action == "links")
		$action = "edit";
	
	$hasright = false;
	$path_parts = explode("/", $path);
	array_shift($path_parts);

	$newpath = "";
	
	for ($n = 0; $n < count($path_parts); $n++)
	{
		$newpath .= "/".$path_parts[$n];
		
		if (is_array($_SESSION['murrix']['rights']['grant'][$action]))
		{
			for ($i = 0; $i < count($_SESSION['murrix']['rights']['grant'][$action]); $i++)
			{
				$path2 = $_SESSION['murrix']['rights']['grant'][$action][$i];
				
				if ($path2 == $newpath)
				{
					if ($action != "create")
						$hasright = true;
					else
					{
						$create_classes2 = $_SESSION['murrix']['rights']['grant']['create_classes'][$i];
						if (empty($create_classes2))
						{
							if ($path2 == $newpath)
								$hasright = true;
						}
						else
						{
							
							foreach ($create_classes as $class)
							{
								if (in_array($class, $create_classes2))
								{
									$hasright = true;
									break;
								}
							}
						}
					}
				}
			}
		}
		
		if (is_array($_SESSION['murrix']['rights']['grantown'][$action]) && $object->creator == $_SESSION['murrix']['user']->id)
		{
			if (in_array($newpath, $_SESSION['murrix']['rights']['grantown'][$action]))
				$hasright = true;
		}
		
		if (is_array($_SESSION['murrix']['rights']['deny'][$action]))
		{
			if (in_array($newpath, $_SESSION['murrix']['rights']['deny'][$action]))
				$hasright = false;
		}
	}
	
	return $hasright;
}

function GetTemplate($show, $path)
{
	global $templates, $templates_override, $abspath;

	switch ($show)
	{
	case TEXT:
		return "$abspath/design/templates/".$templates['text'];
		
	case OBJECT_EDIT:
		return "$abspath/design/templates/".$templates['edit'];
		
	case OBJECT_NEW:
		return "$abspath/design/templates/".$templates['new'];
		
	case OBJECT_LINKS:
		return "$abspath/design/templates/".$templates['links'];
		
	case OBJECT_SHOW:
		$filename = "$abspath/design/templates/".$templates['show'];
		$match_m = 0;
	
		$object = new mObject();
		$object->SetByPath($path);
		
		foreach ($templates_override['show'] as $template)
		{
			$match = 0;
			if (isset($template['match']))
			{
				foreach ($template['match'] as $key => $value)
				{
					switch ($key)
					{
					case "class":
						if ($object->class_name == $value)
							$match++;
						break;
					}
				}
			}
			
			if ($match > $match_m)
			{
				$filename = "$abspath/design/templates/override/".$template['filename'];
				$match_m = $match;
			}
		}
	
		return $filename;
	}
	
	return "$abspath/design/templates/".$templates['show'];
}

function GetTemplateByObject($object)
{
	global $templates, $templates_override, $abspath;

	$filename = "$abspath/design/templates/".$templates['show'];
	$match_m = 0;

	foreach ($templates_override['show'] as $template)
	{
		$match = 0;
		if (isset($template['match']))
		{
			foreach ($template['match'] as $key => $value)
			{
				switch ($key)
				{
				case "class":
					if ($object->class_name == $value)
						$match++;
					break;
				}
			}
		}
		
		if ($match > $match_m)
		{
			$filename = "$abspath/design/templates/override/".$template['filename'];
			$match_m = $match;
		}
	}

	return $filename;
}


function IsAnonymous()
{
	global $anonymous_id;
	return ($_SESSION['murrix']['user']->id == $anonymous_id);
}

function PrintPre($input)
{
	echo "<pre>";
	print_r($input);
	echo "</pre>";
}

function GetInput($name, $default = "")
{
	return (!isset($_GET[$name]) || empty($_GET[$name]) ? (!isset($_POST[$name]) || empty($_POST[$name]) ? $default : $_POST[$name]) : $_GET[$name]);
}

function GetSubfolders($dir)
{
	$folders = array();
	if (!file_exists($dir))
		return $folders;

	$dh = opendir($dir);
	while (false !== ($filename = readdir($dh)))
	{
		if (is_dir("$dir/$filename") && $filename[0] != ".")
			$folders[] = $filename;
	}

	closedir($dh);
	
	if (count($folders) > 0)
	{
		natcasesort($folders);
		$folders = array_values($folders);
	}

	return $folders;
}

function GetSubfiles($dir)
{
	$files = array();
	if (!file_exists($dir))
		return $files;

	$dh = opendir($dir);
	while (false !== ($filename = readdir($dh)))
	{
		if (!is_dir("$dir/$filename") && $filename[0] != ".")
			$files[] = $filename;
	}

	closedir($dh);
	
	if (count($files) > 0)
	{
		natcasesort($files);
		$files = array_values($files);
	}

	return $files;
}

function ShowFile($file)
{
	global $wwwpath;
	
	if (is_binary($file))
	{
		if (exif_imagetype($file) !== false)
		{
			$angle = GetFileAngle($file);
			echo "<img src=\"$wwwpath/output/file.php?file=$file&angle=$angle&maxwidth=930\"/>";
		}
		else
			echo "Binaryfile, not an image";
	}
	else
	{
		$buffer = file_get_contents($file);
		echo nl2br($buffer);
	}
}

function is_binary($link)
{
	$tmpStr  = '';
	@$fp    = fopen($link, 'rb');
	@$tmpStr = fread($fp, 256);
	@fclose($fp);
	
	if ($tmpStr != '')
	{
		$tmpStr = str_replace(chr(10), '', $tmpStr);
		$tmpStr = str_replace(chr(13), '', $tmpStr);
	
		$tmpInt = 0;
	
		for ($i = 0; $i < strlen($tmpStr); $i++)
		{
			if (extension_loaded('ctype'))
			{
				if (!ctype_print($tmpStr[$i]))
					$tmpInt++;
			}
			else
			{
				if (!eregi("[[:print:]]+", $tmpStr[$i]))
					$tmpInt++;
			}
		}
	
		if ($tmpInt > 5)
			return true;
		else
			return false;
	}
	else
		return true;
}


function GetFileAngle($filename)
{
	$result = read_exif_data_raw($filename, 0);
	$angle = 0;
	if (isset($result['IFD0']['Orientation']))
	{
		if ($result['IFD0']['Orientation'] != "Normal (O deg)")
		{
			$angle_array = explode(" ", $result['IFD0']['Orientation']);
			
			if ($angle_array[2] == "CCW")
				$angle = 360 - $angle_array[0];
			else
				$angle = $angle_array[0];
		}
	}
	if (isset($result['IFD1']['Orientation']))
	{
		if ($result['IFD1']['Orientation'] != "Normal (O deg)")
		{
			$angle_array = explode(" ", $result['IFD1']['Orientation']);
			
			if ($angle_array[2] == "CCW")
				$angle = 360 - $angle_array[0];
			else
				$angle = $angle_array[0];
		}
		else
			$angle = 0;
	}
	
	if ($angle >= 360)
		$angle = 0;
	
	return $angle;
}


function img($img, $alt = "")
{
	global $abspath, $wwwpath;

	$parent_path = substr($abspath, 0, strlen($abspath)-strlen($wwwpath));
	list($width, $height, $type, $attr) = getimagesize("$parent_path/$img");

	return "<img src=\"$img\" alt=\"$alt\" style=\"width: ".$width."px; height: ".$height."px;\"/>";
}

function SplitFilepath($filepath)
{
	preg_match("/([^\/]+)\.(.+)$/", basename($filepath), $m);
	
	$result['fullpath'] = $filepath;
	$result['filename'] = $m[0];
	$result['name'] = $m[1];
	$result['extension'] = $result['ext'] = strtolower($m[2]);
	
	return $result;
}

function DelArg($instring, $argname)
{
        $inargs = split("&", $instring);

        for ($i = 0; $i < count($inargs); $i++)
        {
                if (!preg_match("/^$argname=/", $inargs[$i]))
                {
                        if ($i > 0)
                                $outstring .= "&";

                        $outstring .= $inargs[$i];
                }
        }

        return $outstring;
}

function safestrtotime($s)
{
	$basetime = 0;
	if (preg_match("/19(\d\d)/", $s, $m) && ($m[1] < 70))
	{
		$s = preg_replace("/19\d\d/", 1900 + $m[1]+68, $s);
		$basetime = 0x80000000 + 1570448;
	}
       return $basetime + strtotime ($s);
}



function DownloadSize($size)
{
	$sizes = array('b', 'kb', 'mb', 'gb', 'tb', 'pb', 'eb');
	$ext = $sizes[0];

	for ($i=1; (($i < count($sizes)) && ($size >= 1024)); $i++)
	{
		$size = $size / 1024;
		$ext  = $sizes[$i];
	}

	return round($size, 2)."&nbsp;".$ext;
}

?>
