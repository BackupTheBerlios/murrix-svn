<?

class csCd extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$path = $stdin;
		
		if (empty($path))
		{
			$home_id = $_SESSION['murrix']['user']->home_id;
			
			if ($home_id > 0)
			{
				$home = new mObject($home_id);
				$path = $home->getPath();
			}
			else
				return true;
		}
			
		if ($path == ".")
			return true;
			
		if ($path == "..")
		{
			$path = getParentPath($_SESSION['murrix']['path']);
		}
		else if ($path{0} != "/")
		{
			$path = $_SESSION['murrix']['path']."/$path";
		}
	
		global $root_id;
		$node_id = getNode($path);
		
		$invalid = false;
		if ($node_id == $root_id)
		{
			$root_obj = new mObject($root_id);
			
			if ($root_obj->getPath() != $path)
				$invalid = true;
		}
		else if ($node_id <= 0)
			$invalid = true;
			
		if ($invalid)
		{
			$stderr = "No such path \"$path\"";
			return true;
		}
		
		$_SESSION['murrix']['path'] = $path;
		$stdout = "Entered \"$path\"";
		
		if ($response != null)
			$system->TriggerEventIntern($response, "newlocation", array("path" => $path));
			
		return true;
	}
}

?>