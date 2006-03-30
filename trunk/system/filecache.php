<?

$filecache = array();

function registerFileCache($name, $lifetime)
{
	global $db_prefix, $filecache;
	
	if (!isset($filecache[$name]))
	{
		$query = "SELECT * FROM `".$db_prefix."filecache` WHERE name = '$name' AND language = '".$_SESSION['murrix']['language']."'";
		$result = mysql_query($query) or die("registerFileCache: " . mysql_errno() . " " . mysql_error());
		
		if (mysql_num_rows($result) > 0)
		{
			$filecache[$name] = mysql_fetch_array($result, MYSQL_ASSOC);
		}
		else
		{
			$query = "INSERT INTO `".$db_prefix."filecache` (name, lifetime, language) VALUES('$name', '$lifetime', '".$_SESSION['murrix']['language']."')";

			$result = mysql_query($query);
			if (!$result)
			{
				$string = "<b>An error occured while inserting</b><br>";
				$string .= "<b>Table:</b> `".$db_prefix."filecache`<br>";
				$string .= "<b>Query:</b> $query<br>";
				$string .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
				$string .= "<b>Error:</b> " . mysql_error() . "<br>";
				echo $string;
				return false;
			}

			$filecache[$name]['id'] = mysql_insert_id();
			$filecache[$name]['name'] = $name;
			$filecache[$name]['lifetime'] = $lifetime;
			$filecache[$name]['created'] = "0000-00-00 00:00:00";
			$filecache[$name]['language'] = $_SESSION['murrix']['language'];
		}
	}
}

function getFileCache($name)
{
	global $abspath, $db_prefix, $filecache;
	
	$filename = "$abspath/cache/".$_SESSION['murrix']['language'].".$name.html";
	
	// Check if file has timedout
	if ($filecache[$name]['created'] == "0000-00-00 00:00:00")
		return false;
	//echo $filecache[$name]['created'];
	/*$query = "SELECT obj.id FROM `".$db_prefix."objects` AS obj, `".$db_prefix."filecache_nodes` AS fn WHERE obj.`created` >= '".$filecache[$name]['created']."' AND obj.`node_id` = fn.`node_id`";
	
	$result = mysql_query($query) or die("getFileCache: " . mysql_errno() . " " . mysql_error());
	
	if (mysql_num_rows($result) > 0)
		return false;*/
	
	$timestamp = strtotime($filecache[$name]['lifetime'], strtotime($filecache[$name]['created']));
	//echo date("Y-m-d H:i:s", $timestamp).">".date("Y-m-d H:i:s");
	if ($timestamp > time())
	{
		if (file_exists($filename))
			return file_get_contents($filename);
	}
		
	return false;
}

function startFileCache($name)
{
	global $abspath, $filecache;
	
	$filename = "$abspath/cache/".$_SESSION['murrix']['language'].".$name.html";
	
	$filecache[$name]['file'] = fopen($filename, "w+");
	
	ob_start();
}

function stopFileCache($name, $node_ids, $created = "now")
{
	global $db_prefix, $filecache;

	$buffer = ob_get_end();
	
	fwrite($filecache[$name]['file'], $buffer);

	fclose($filecache[$name]['file']);
	
	unset($filecache[$name]['file']);

	$datetime = date("Y-m-d H:i:s", strtotime($created));
	$query = "UPDATE `".$db_prefix."filecache` SET created='$datetime' WHERE id = '".$filecache[$name]['id']."'";
	
	$result = mysql_query($query);
	if (!$result)
	{
		$message = "<b>An error occured while updateing</b><br/>";
		$message .= "<b>Table:</b> filecache<br/>";
		$message .= "<b>Query:</b> $query<br/>";
		$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
		$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
		return $message;
	}
	
	$filecache[$name]['created'] = $datetime;

	$query = "DELETE FROM `".$db_prefix."filecache_nodes` WHERE filecache_id = '".$filecache[$name]['id']."'";
	
	$result = mysql_query($query);
	if (!$result)
	{
		$string = "<b>An error occured while deleting</b><br>";
		$string .= "<b>Table:</b> `".$db_prefix."filecache_nodes`<br>";
		$string .= "<b>Query:</b> $query<br>";
		$string .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
		$string .= "<b>Error:</b> " . mysql_error() . "<br>";
		echo $string;
		return false;
	}

	foreach ($node_ids as $node_id)
	{
		$query = "INSERT INTO `".$db_prefix."filecache_nodes` (filecache_id, node_id) VALUES('".$filecache[$name]['id']."', '$node_id')";

		$result = mysql_query($query);
		if (!$result)
		{
			$string = "<b>An error occured while inserting</b><br>";
			$string .= "<b>Table:</b> `".$db_prefix."filecache_nodes`<br>";
			$string .= "<b>Query:</b> $query<br>";
			$string .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
			$string .= "<b>Error:</b> " . mysql_error() . "<br>";
			echo $string;
			return false;
		}
	}

	return $buffer;
}

function clearNodeFileCache($node_id)
{
	global $db_prefix, $filecache;
	
	$query = "UPDATE `".$db_prefix."filecache` AS f, `".$db_prefix."filecache_nodes` AS fn SET f.`created`='0000-00-00 00:00:00' WHERE f.id = fn.filecache_id AND fn.node_id = '$node_id'";
	
	$result = mysql_query($query);
	if (!$result)
	{
		$message = "<b>An error occured while updateing</b><br/>";
		$message .= "<b>Table:</b> filecache<br/>";
		$message .= "<b>Query:</b> $query<br/>";
		$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
		$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
		return $message;
	}
}

?>