<?

function fetch($query, $debug = false)
{
	if (isset($_SESSION['murrix']['querycache'][$query]) && !$debug)
		return $_SESSION['murrix']['querycache'][$query];

	$query2 = $query;
	$commands = array("FETCH", "WHERE", "NODESORTBY", "SORTBY");
	$cmdstr = implode("|", $commands);

	$links = false;
	$vars = 0;
	$return = "object";
	$sort = array();
	$vars_array = array();

	$nodesortby = "";
	
	foreach ($commands as $ord)
	{
		if (preg_match("/^[ ]*($ord) (.+?)( ($cmdstr|$)|$)/", $query, $matches))
		{
			switch ($matches[1])
			{
				case "FETCH":
					switch (trim($matches[2]))
					{
					case "count":
						$select = "SELECT count(objects.id) AS count ";
						$return = "count";
						break;
	
					case "node":
						$select = "SELECT objects.id AS id, objects.node_id AS node_id, objects.language AS language, objects.version AS version ";
						$return = "node";
						break;
	
					case "object":
					default:
						$select = "SELECT objects.id AS id ";
						$return = "object";
						break;
					}
					break;
	
				case "WHERE":
					$org_where = trim($matches[2]);
					if (preg_match_all("/[ ]*(.+?)( AND| OR|$)/", $org_where, $wmatches))
					{
						$wmatches = $wmatches[1];
						//PrintPre($wmatches);
						foreach ($wmatches AS $match)
						{
							$match = trim($match);
							if ($match{0} == "(")
								$match = substr($match, 1, strlen($match)-1);
	
							$parts = explode(":", $match, 2);
	
							$invert = "";
							if ($parts[0]{0} == "!")
							{
								$invert = "!";
								$parts[0] = substr($parts[0], 1, strlen($parts[0])-1);
							}
	
							switch ($parts[0])
							{
							case "property":
								$org_where = str_replace($match, "$invert(objects.".$parts[1].")", $org_where);
								break;
	
							case "var":
								$parts2 = explode("=", $parts[1]);
	
								if (!isset($vars_array[$parts2[0]]))
								{
									$vars++;
									$vars_array[$parts2[0]] = $vars;
								}
								$num = $vars_array[$parts2[0]];
								$org_where = str_replace($match, "values$num.data$invert=".$parts2[1], $org_where);
								break;
	
							case "link":
								$parts2 = explode("=", $parts[1]);
								switch ($parts2[0])
								{
								case "node_top":
									$org_where = str_replace($match, "$invert(links.node_top=".$parts2[1]." AND links.node_bottom=objects.node_id)", $org_where);
									break;
	
								case "node_bottom":
									$org_where = str_replace($match, "$invert(links.node_bottom=".$parts2[1]." AND links.node_top=objects.node_id)", $org_where);
									break;
	
								case "type":
									$org_where = str_replace($match, "$invert(links.type=".$parts2[1].")", $org_where);
									break;
	
								case "node_id":
								default:
									$org_where = str_replace($match, "$invert((links.node_bottom=".$parts2[1]." AND links.node_top=objects.node_id) OR (links.node_top=".$parts2[1]." AND links.node_bottom=objects.node_id))", $org_where);
									break;
								}
								$links = true;
								break;
							}
						}
					}
	
					$where_more = "";
					foreach ($vars_array as $key => $value)
					{
						$where_more .= "(values$value.object_id=objects.id AND values$value.var_id=vars$value.id AND vars$value.name='$key') AND ";
					}
	
					$where = "WHERE $where_more ($org_where)";
					break;
	
				case "NODESORTBY":
					$org_sort = trim($matches[2]);
					if (preg_match_all("/[ ]*(.+?)(,|$)/", $org_sort, $wmatches))
					{
						$wmatches = $wmatches[1];
	
						foreach ($wmatches AS $match)
						{
							$match = trim($match);
	
							$parts = explode(":", $match, 2);
	
							$invert = " DESC";
							if ($parts[0]{0} == "!")
							{
								$invert = " ASC";
								$parts[0] = substr($parts[0], 1, strlen($parts[0])-1);
							}
	
							switch ($parts[0])
							{
							case "property":
								$org_sort = str_replace($match, "objects.".$parts[1].$invert, $org_sort);
								break;
							}
						}
					}
						$nodesortby = "ORDER BY $org_sort";
					break;
	
				case "SORTBY":
					$org_sort = trim($matches[2]);
					if (preg_match_all("/[ ]*(.+?)(,|$)/", $org_sort, $wmatches))
					{
						$wmatches = $wmatches[1];
	
						foreach ($wmatches AS $match)
						{
							$match = trim($match);
	
							$parts = explode(":", $match, 2);
	
							$invert = false;
							if ($parts[0]{0} == "!")
							{
								$invert = true;
								$parts[0] = substr($parts[0], 1, strlen($parts[0])-1);
							}
	
							switch ($parts[0])
							{
							case "property":
								$sort[] = array("property:".$parts[1], $invert);
								//$org_sort = str_replace($match, "objects.".$parts[1].($invert ? "" : " DESC"), $org_sort);
								break;
	
							case "var":
								$sort[] = array($parts[1], $invert);
								break;
							}
						}
					}
					//if (!empty($org_sort))
					//	$sortby = "ORDER BY $org_sort";
					break;
			}
	
			$len = strlen($matches[0]) - strlen($matches[3]);
			$query = trim(substr($query, $len, strlen($query)-$len));
		}
		else
		{
			if (!empty($query))
			{
				echo "nåt galet hände..\n";
				echo $query;
			}
		}
	}
	
	global $db_prefix;

	$from = "FROM `".$db_prefix."objects` AS `objects`";
	
	for ($n = 1; $n <= $vars; $n++)
		$from .= ", `".$db_prefix."vars` AS `vars$n`, `".$db_prefix."values` AS `values$n`";
		
	if ($links)
		$from .= ", `".$db_prefix."links` AS `links`";

	$sql = "$select $from $where $nodesortby";

	$result = mysql_query($sql) or die("fetch " . mysql_errno() . " " . mysql_error());

	if ($return == "count")
	{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		//$_SESSION['debug'] += microtime_float()-$time;
		$_SESSION['murrix']['querycache'][$query2] = $row['count'];
		return $row['count'];
	}

	if ($return == "object")
	{
		$objects = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$object = new mObject();
			if (!$object->loadByObjectId($row['id']))
				echo $object->getLastError();
			$objects[] = $object;
		}
	}
	else if ($return == "node")
	{
		$objects = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$object = new mObject();
			$object->node_id = $row['node_id'];
			$object->id = $row['id'];
			$object->version = $row['version'];
			$object->language = $row['language'];
			$objects[] = $object;
		}

		$nodes = array();
		$node_list = array();
		foreach ($objects as $object)
		{
			// We have a prior version already
			if (isset($node_list[$object->getNodeId()]))
			{
				if ($node_list[$object->getNodeId()] != $_SESSION['murrix']['language']) // Not a perfect match
				{
					if ($object->getLanguage() == $_SESSION['murrix']['language']) // We have a better match
					{
						$nodes[$object->getNodeId()] = $object;
						$node_list[$object->getNodeId()] = $object->getLanguage();
					}
				}
				/*else // Perfect language match
				{
					if ($node_list[$object->getNodeId()]->version < $object->version)// Do we have a better version
					{
						$nodes[$object->getNodeId()] = $object;
						$node_list[$object->getNodeId()] = $object->getLanguage();
					}
				}*/
			}
			else // We have no prior match
			{
				$nodes[$object->getNodeId()] = $object;
				$node_list[$object->getNodeId()] = $object->getLanguage();
			}
		}

		$objects2 = array_values($nodes);
		
		$objects = array();
		foreach ($objects2 as $object2)
		{
			$object = new mObject();
			if (!$object->loadByObjectId($object2->getId()))
				echo $object->getLastError();
			$objects[] = $object;
		}
	}
	
	if (!empty($sort))
	{
		$sort = array_reverse($sort);
		foreach ($sort as $sortby)
		{
			SortBy(&$objects, $sortby[0], $sortby[1]);
		}
	}
	
	$_SESSION['murrix']['querycache'][$query2] = $objects;
	return $objects;
}

function SortBy(&$list, $sortby, $invert = false)
{
	switch ($sortby)
	{
	case "property:name":
		usort(&$list, "SortByName");
		break;
	case "property:class":
		usort(&$list, "SortByClass");
		break;
	case "property:language":
		usort(&$list, "SortByLanguage");
		break;
	case "property:icon":
		usort(&$list, "SortByIcon");
		break;
	case "property:version":
		usort(&$list, "SortByVersion");
		break;
	case "property:created":
		usort(&$list, "SortByCreated");
		break;
	case "property:node_created":
		usort(&$list, "SortByNodeCreated");
		break;
	case "property:user_id":
		usort(&$list, "SortByUser");
		break;
	case "property:group_id":
		usort(&$list, "SortByGroup");
		break;
	case "property:rights":
		usort(&$list, "SortByRights");
		break;
	default:
		mergesort(&$list, $sortby);
		break;
	}

	if ($invert)
		$list = array_reverse($list);
}

function SortByName($a, $b) { return strnatcasecmp($a->getName(), $b->getName()); }
function SortByClass($a, $b) { return strnatcasecmp($a->getClassName(), $b->getClassName()); }
function SortByLanguage($a, $b) { return strnatcasecmp($a->getLanguage(), $b->getLanguage()); }
function SortByIcon($a, $b) { return strnatcasecmp($a->getIcon(), $b->getIcon()); }
function SortByVersion($a, $b) { return strnatcasecmp($a->getVersion(), $b->getVersion()); }
function SortByCreated($a, $b) { return strnatcasecmp($a->getCreated(), $b->getCreated()); }
function SortByNodeCreated($a, $b) { return strnatcasecmp($a->getNodeCreated(), $b->getNodeCreated()); }
function SortByUser($a, $b) { return strnatcasecmp($a->getUserId(), $b->getUserId()); }
function SortByGroup($a, $b) { return strnatcasecmp($a->getGroupId(), $b->getGroupId()); }
function SortByRights($a, $b) { return strnatcasecmp($a->getRights(), $b->getRights()); }

function mergesort(&$array, $sortby)
{
	// Arrays of size < 2 require no action.
	if (count($array) < 2)
		return;
		
	// Split the array in half
	$halfway = count($array) / 2;
	$array1 = array_slice($array, 0, $halfway);
	$array2 = array_slice($array, $halfway);
	
	// Recurse to sort the two halves
	mergesort($array1, $sortby);
	mergesort($array2, $sortby);
	
	// If all of $array1 is <= all of $array2, just append them.
	$last = end($array1);
	if (strnatcasecmp($last->getVarValue($sortby), $array2[0]->getVarValue($sortby)) < 1)
	{
		$array = array_merge($array1, $array2);
		return;
	}
	
	// Merge the two sorted arrays into a single sorted array
	$array = array();
	$ptr1 = $ptr2 = 0;
	while ($ptr1 < count($array1) && $ptr2 < count($array2))
	{
		if (strnatcasecmp($array1[$ptr1]->getVarValue($sortby), $array2[$ptr2]->getVarValue($sortby)) < 1)
			$array[] = $array1[$ptr1++];
		else
			$array[] = $array2[$ptr2++];
	}
	
	// Merge the remainder
	while ($ptr1 < count($array1))
		$array[] = $array1[$ptr1++];
		
	while ($ptr2 < count($array2))
		$array[] = $array2[$ptr2++];
}

?>