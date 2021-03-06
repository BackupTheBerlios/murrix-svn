<?

class csUlist extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$system)
	{
		$user = new mUser();
		$users = $user->getList();
		
		if ($args == "-l")
		{
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
				$stdout .= "<td>Created</td>";
				$stdout .= "<td>Last login</td>";
				$stdout .= "<td>Last activity</td>";
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
						$stdout .= "<td>".cmd($home->getPath(), "exec=show&node_id=".$home->getNodeId())."</td>";
					}
					else
						$stdout .= "<td></td>";
						
					$stdout .= "<td>".$user->groups."</td>";
					$stdout .= "<td>".str_replace(" ", "&nbsp;", $user->created)."</td>";
					
					if ($user->last_login == "0000-00-00 00:00:00")
						$stdout .= "<td>Never</td>";
					else
						$stdout .= "<td>".str_replace(" ", "&nbsp;", $user->last_login)."</td>";
						
					if ($user->last_active == "0000-00-00 00:00:00")
						$stdout .= "<td>Never</td>";
					else
						$stdout .= "<td>".str_replace(" ", "&nbsp;", $user->last_active)."</td>";
						
					$stdout .= "</tr>";
				}
				$stdout .= "</table>";
			}
		}
		else
		{
			foreach ($users as $user)
				$stdout .= $user->username." ";
		}
		
		return true;
	}
}

?>