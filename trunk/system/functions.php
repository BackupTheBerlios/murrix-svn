<?

function getAge($birthdate)
{
	list($byear, $bmonth, $bday) = explode("-", $birthdate);
	$year = date("Y");
	$month = date("m");
	$day = date("d");
	
	if ($year > $byear)
	{
		if ($month >= $bmonth)
		{
			if ($day >= $bday)
				return $year-$byear;
		}
		
		return $year-$byear-1;
	}
	
	return 0;
}

function splitArgs($args)
{
	$parts = explode(" ", $args);
	
	$matches = array();
	
	$started = false;
	$string = "";
	
	foreach ($parts as $part)
	{
		if ($part{0} == '"')
			$started = true;
			
		$part2 = str_replace('"', '', $part);
		
		if ($started)
			$string .= "$part2 ";
		else
			$matches[] = $part2;
			
		if ($part{strlen($part)-1} == '"')
		{
			$matches[] = trim($string);
			$string = "";
			$started = false;
		}
	}
	
	return $matches;
}

function colour($tint)
{
	$frag = range(0,255);
	
	$red = "";
	$green = "";
	$blue = "";
	
	while (true)
	{
		$red = $frag[mt_rand(0, count($frag)-1)];
		$green = $frag[mt_rand(0, count($frag)-1)];
		$blue = $frag[mt_rand(0, count($frag)-1)];
		
		switch ($tint)
		{
		case 'light':
		if ((($red + $green + $blue) / 3) >= 200) break 2;
		break;
		
		case 'dark' :
		default:
		if ((($red + $green + $blue) / 3) <= 50) break 2;
		break;
		}
	}
	
	return sprintf("#%02s%02s%02s", dechex($red), dechex($green), dechex($blue));
}

function getClassList($fullinfo = false)
{
	global $db_prefix;
	
	if ($fullinfo)
	{
		$query = "SELECT * FROM `".$db_prefix."classes` ORDER BY name";
		$result = mysql_query($query) or die("getClassList: " . mysql_errno() . " " . mysql_error());
	
		$list = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$list[] = $row;
	
		return $list;
	}
	
	$query = "SELECT name FROM `".$db_prefix."classes` ORDER BY name";
	$result = mysql_query($query) or die("getClassList: " . mysql_errno() . " " . mysql_error());

	$list = array();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		$list[] = $row['name'];

	return $list;
}

function getEventDate($object, $now = "now")
{
	$event_date = $object->getVarValue("date");
	
	if ($object->getClassName() == "event")
	{
		$now_stamp = strtotime($now);
		
		$now_year = date("Y", $now_stamp);
		$now_month = date("m", $now_stamp);
		$now_day = date("d", $now_stamp);
	
		list($year, $month, $day) = explode("-", $event_date);

		$yearly = ($object->getVarValue("reoccuring_yearly", true) == 1);
		$monthly = ($object->getVarValue("reoccuring_monthly", true) == 1);

		if ($yearly && $monthly)
			return "$now_year-$now_month-$day";

		if ($yearly)
			return "$now_year-$month-$day";

		if ($monthly)
			return "$year-$now_month-$day";
	}

	return $event_date;
}

function getReadable($objects)
{
	if (isAdmin())
		return $objects;

	$objects_readable = array();
	for ($n = 0; $n < count($objects); $n++)
	{
		if ($objects[$n]->hasRight("read"))
			$objects_readable[] = $objects[$n];
	}
	return $objects_readable;
}

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
			$new[$key] = utf8d($d);
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

function SplitFilepath($filepath)
{
	preg_match("/([^\/]+)\.(.+)$/", basename($filepath), $m);
	
	$result['fullpath'] = $filepath;
	$result['filename'] = $m[0];
	$result['name'] = $m[1];
	$result['extension'] = $result['ext'] = $m[2];
	
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
