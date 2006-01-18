<?

/* == Public functions == */

function getPaths($node_id, $name)
{
	path_db_load();

	if (isset($_SESSION['murrix']['pathcache_path'][$node_id]))
		return $_SESSION['murrix']['pathcache_path'][$node_id];

	// We have a cachemiss, resolve manually

	$array = array();

	global $db_prefix, $root_id;

	$query = "SELECT DISTINCT(objects.language) AS language, objects.node_id AS node_id, objects.name AS name FROM `".$db_prefix."objects` AS `objects`, `".$db_prefix."links` AS `links` WHERE links.type='sub' AND links.node_top=objects.node_id AND links.node_bottom='$node_id' GROUP BY objects.node_id, objects.language, objects.version DESC, objects.name";

	$result = mysql_query($query) or die("getPaths: " . mysql_errno() . " " . mysql_error());

	$parents = array();
	$rows = array();
	if (mysql_num_rows($result) == 0)
		return array("/$name"); // This is the root object
	else
	{
		$parents = array();
		$node_list = array();
		
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			// We have a prior version already
			if (isset($node_list[$row['node_id']]))
			{
				if ($node_list[$row['node_id']] != $_SESSION['murrix']['language']) // Not a perfect match
				{
					if ($row['language'] == $_SESSION['murrix']['language']) // We have a better match
					{
						$parents[$row['node_id']] = $row;
						$node_list[$row['node_id']] = $row['language'];
					}
				}
			}
			else // We have no prior match
			{
				$parents[$row['node_id']] = $row;
				$node_list[$row['node_id']] = $row['language'];
			}
		}

		$parents = array_values($parents);
	}

	foreach ($parents as $parent)
	{
		$parent_id = $parent['node_id'];
		$parent_name = $parent['name'];

		$array = array_merge(getPaths($parent_id, $parent_name), $array);
	}

	$paths = array();
	foreach ($array as $path)
		$paths[] = "$path/$name";
		
	path_add_to_cache($node_id, $paths);

	return $paths;
}

function getNode($path, $language = "")
{
	path_db_load();

	if (isset($_SESSION['murrix']['pathcache_node'][$path]))
		return $_SESSION['murrix']['pathcache_node'][$path];

	// We have a cachemiss, resolve manually
	global $db_prefix, $root_id;
	
	// Nothing found i cache
	if (empty($path)) // We are att the root
		return -2;

	$parent_id = getNode(GetParentPath($path), $language);

	if ($parent_id == -2)
		return $root_id;
	else if ($parent_id == -1)
		return -1;

	$name = basename($path);

	if (empty($language))
		$language = $_SESSION['murrix']['language'];
		
	$query = "SELECT objects.node_id AS node_id FROM `".$db_prefix."objects` AS `objects`, `".$db_prefix."links` AS `links` WHERE objects.name = '$name' AND links.type = 'sub' AND links.node_top = '$parent_id' AND links.node_bottom = objects.node_id ORDER BY objects.version ASC LIMIT 1";

	$result = mysql_query($query) or die("getNode: " . mysql_errno() . " " . mysql_error());
	if (mysql_num_rows($result) == 0)
		return -1;
	else
	{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$node_id = $row['node_id'];
	}

	// We want to cache all paths
	getPaths($node_id, $name);
	
	return $node_id;
}

function updatePaths($node_id)
{
	path_del_from_cache($node_id);
}


// For backwardscompat.
function resolvePath($path)
{
	return getNode($path);
}


/* == Private functions == */

function path_add_to_cache($node_id, $paths)
{
	global $db_prefix;

	foreach ($paths as $path)
	{
		if (isset($_SESSION['murrix']['pathcache_node'][$path]))
		{
			if ($_SESSION['murrix']['pathcache_node'][$path] == $node_id)
				continue;
			else
				path_del_from_cache($node_id);
		}
	
		$query = "INSERT INTO `".$db_prefix."pathcache` (node_id, path) VALUES('$node_id', '$path')";
		if (!($result = mysql_query($query)))
		{
			$message = "<b>An error occured while inserting</b><br/>";
			$message .= "<b>Table:</b> ".$db_prefix."pathcache`<br/>";
			$message .= "<b>Query:</b> $query<br/>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
			echo $message;
			return false;
		}

		if (!isset($_SESSION['murrix']['pathcache_path'][$node_id]))
			$_SESSION['murrix']['pathcache_path'][$node_id] = array($path);
		else
			array_push($_SESSION['murrix']['pathcache_path'][$node_id], $path);

		$_SESSION['murrix']['pathcache_node'][$path] = $node_id;
	}
}

function path_del_from_cache($node_id)
{
	global $db_prefix;
	
	$query = "SELECT path FROM `".$db_prefix."pathcache` WHERE node_id='$node_id'";

	if ($result = mysql_query($query))
	{
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$query = "DELETE FROM `".$db_prefix."pathcache` WHERE path LIKE '".$row['path']."%'";
			mysql_query($query);
		}
	}

	unset($_SESSION['murrix']['pathcache_path']);
	unset($_SESSION['murrix']['pathcache_node']);
}

function path_db_load()
{
	if (!isset($_SESSION['murrix']['pathcache_path']) || !isset($_SESSION['murrix']['pathcache_node']))
	{
		global $db_prefix;
	
		$query = "SELECT node_id, path FROM `".$db_prefix."pathcache` ORDER BY node_id";
	
		if (!($result = mysql_query($query)))
			return array();

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			if (!isset($_SESSION['murrix']['pathcache_path'][$row['node_id']]))
				$_SESSION['murrix']['pathcache_path'][$row['node_id']] = array($row['path']);
			else
				array_push($_SESSION['murrix']['pathcache_path'][$row['node_id']], $row['path']);

			$_SESSION['murrix']['pathcache_node'][$row['path']] = $row['node_id'];
		}
	}
}

?>