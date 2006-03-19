<?

function registerFileCache($name, $lifetime)
{
	global $db_prefix;
	
	if (!isset($_SESSION['murrix']['filecache'][$name]))
	{
		$query = "SELECT * FROM `".$db_prefix."filecache` WHERE name = '$name'";
		$result = mysql_query($query) or die("registerFileCache: " . mysql_errno() . " " . mysql_error());
		
		if (mysql_num_rows($result) > 0)
		{
			$_SESSION['murrix']['filecache'][$name] = mysql_fetch_array($result, MYSQL_ASSOC);
		}
		else
		{
			$query = "INSERT INTO `".$db_prefix."filecache` (name, lifetime) VALUES('$name', '$lifetime')";

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

			$_SESSION['murrix']['filecache'][$name]['id'] = mysql_insert_id();
			$_SESSION['murrix']['filecache'][$name]['name'] = $name;
			$_SESSION['murrix']['filecache'][$name]['lifetime'] = $lifetime;
			$_SESSION['murrix']['filecache'][$name]['created'] = "0000-00-00 00:00:00";
		}
	}
}

function getFileCache($name)
{
	global $abspath, $db_prefix;
	
	$filename = "$abspath/cache/$name.html";
	
	// Check if file has timedout
	if ($_SESSION['murrix']['filecache'][$name]['created'] == "0000-00-00 00:00:00")
		return false;
	
	/*$query = "SELECT obj.id FROM `".$db_prefix."objects` AS obj, `".$db_prefix."filecache_nodes` AS fn WHERE obj.`created` >= '".$_SESSION['murrix']['filecache'][$name]['created']."' AND obj.`node_id` = fn.`node_id`";
	
	$result = mysql_query($query) or die("getFileCache: " . mysql_errno() . " " . mysql_error());
	
	if (mysql_num_rows($result) > 0)
		return false;*/
	
	$timestamp = strtotime($_SESSION['murrix']['filecache'][$name]['lifetime'], strtotime($_SESSION['murrix']['filecache'][$name]['created']));
	
	if ($timestamp > time())
	{
		if (file_exists($filename))
			return file_get_contents($filename);
	}
		
	return false;
}

function startFileCache($name)
{
	global $abspath;
	
	$filename = "$abspath/cache/$name.html";
	
	$_SESSION['murrix']['filecache'][$name]['file'] = fopen($filename, "w+");
	
	ob_start();
}

function stopFileCache($name, $node_ids)
{
	global $db_prefix;

	$buffer = ob_get_end();
	
	fwrite($_SESSION['murrix']['filecache'][$name]['file'], $buffer);

	fclose($_SESSION['murrix']['filecache'][$name]['file']);
	
	unset($_SESSION['murrix']['filecache'][$name]['file']);

	$datetime = date("Y-m-d H:i:s");
	$query = "UPDATE `".$db_prefix."filecache` SET created='$datetime' WHERE id = '".$_SESSION['murrix']['filecache'][$name]['id']."'";
	
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
	
	$_SESSION['murrix']['filecache'][$name]['created'] = $datetime;

	$query = "DELETE FROM `".$db_prefix."filecache_nodes` WHERE filecache_id = '".$_SESSION['murrix']['filecache'][$name]['id']."'";
	
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
		$query = "INSERT INTO `".$db_prefix."filecache_nodes` (filecache_id, node_id) VALUES('".$_SESSION['murrix']['filecache'][$name]['id']."', '$node_id')";

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
	global $db_prefix;
	
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