<?

class csUsers extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		$user = new mUser();
		
		$users = $user->getList();
		$stdout .= "total ".count($users)."\n";
		if (count($users) > 0)
		{
			$stdout .= "<table cellspacing=\"0\">";
			$stdout .= "<tr class=\"table_title\">";
			$stdout .= "<td>Id</td>";
			$stdout .= "<td>Name</td>";
			$stdout .= "<td>Username</td>";
			$stdout .= "<td>Home</td>";
			$stdout .= "<td>Groups</td>";
			$stdout .= "</tr>";
			foreach ($users as $user)
			{
				$stdout .= "<tr>";
				$stdout .= "<td>".$user->id."</td>";
				$stdout .= "<td>".$user->name."</td>";
				$stdout .= "<td>".$user->username."</td>";
				
				if ($user->home_id > 0)
				{
					$home = new mObject($user->home_id);
					$stdout .= "<td>".cmd($home->getPath(), "Exec('show','zone_main',Hash('node_id','".$home->getNodeId()."'))")."</td>";
				}
				else
					$stdout .= "<td></td>";
					
				$stdout .= "<td>".$user->groups."</td>";
				$stdout .= "</tr>";
			}
			$stdout .= "</table>";
		}
		
		return true;
	}
}

?>