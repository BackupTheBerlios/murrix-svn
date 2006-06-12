<?

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

function GetRightsRecursive($object)
{
	$parents = fetch("FETCH node WHERE link:node_bottom='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='group' NODESORTBY property:version SORTBY property:name");
	
	$rights = array();
	foreach ($parents as $parent)
	{
		$children = fetch("FETCH node WHERE link:node_top='".$parent->getNodeId()."' AND link:type='sub' AND property:class_name='right' NODESORTBY property:version SORTBY property:name");
		
		$rights = array_merge($rights, $children, GetRightsRecursive($parent));
	}
	
	return $rights;
}

function isAnonymous()
{
	global $anonymous_id;
	return ($_SESSION['murrix']['user']->id == $anonymous_id);
}

function isAdmin()
{
	$user_groups = $_SESSION['murrix']['user']->getGroups();
	return in_array("admins", $user_groups);
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

function imageCopyResampleBicubic($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
{
  $scaleX = ($src_w - 1) / $dst_w;
  $scaleY = ($src_h - 1) / $dst_h;

  $scaleX2 = $scaleX / 2.0;
  $scaleY2 = $scaleY / 2.0;

  $tc = imageistruecolor($src_img);

  for ($y = $src_y; $y < $src_y + $dst_h; $y++)
  {
   $sY  = $y * $scaleY;
   $siY  = (int) $sY;
   $siY2 = (int) $sY + $scaleY2;

   for ($x = $src_x; $x < $src_x + $dst_w; $x++)
   {
     $sX  = $x * $scaleX;
     $siX  = (int) $sX;
     $siX2 = (int) $sX + $scaleX2;

     if ($tc)
     {
       $c1 = imagecolorat($src_img, $siX, $siY2);
       $c2 = imagecolorat($src_img, $siX, $siY);
       $c3 = imagecolorat($src_img, $siX2, $siY2);
       $c4 = imagecolorat($src_img, $siX2, $siY);

       $r = (($c1 + $c2 + $c3 + $c4) >> 2) & 0xFF0000;
       $g = ((($c1 & 0xFF00) + ($c2 & 0xFF00) + ($c3 & 0xFF00) + ($c4 & 0xFF00)) >> 2) & 0xFF00;
       $b = ((($c1 & 0xFF)  + ($c2 & 0xFF)  + ($c3 & 0xFF)  + ($c4 & 0xFF))  >> 2);

       imagesetpixel($dst_img, $dst_x + $x - $src_x, $dst_y + $y - $src_y, $r+$g+$b);
     }
     else
     {
       $c1 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY2));
       $c2 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY));
       $c3 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY2));
       $c4 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY));

       $r = ($c1['red']  + $c2['red']  + $c3['red']  + $c4['red']  ) << 14;
       $g = ($c1['green'] + $c2['green'] + $c3['green'] + $c4['green']) << 6;
       $b = ($c1['blue']  + $c2['blue']  + $c3['blue']  + $c4['blue'] ) >> 2;

       imagesetpixel($dst_img, $dst_x + $x - $src_x, $dst_y + $y - $src_y, $r+$g+$b);
     }
   }
  }
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
