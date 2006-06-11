<?

class csPasswd extends CScript
{
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		switch ($this->stage)
		{
			case 1:
				$this->password1 = $stdin;
				$stdout = ucf(i18n("enter new password again:"));
				$this->stage = 2;
				return false;
				
			case 2:
				if ($this->password1 != $stdin)
					$stdout = ucf(i18n("passwords do not match, aborting"));
				else
				{
					$ret = changePassword($this->user_node_id, $stdin);
					if ($ret !== true)
					{
						$stderr = $ret;
						$this->stage = 0;
						return true;
					}
					else
					{
						$user = new mObject($this->user_node_id);
						$stdout = ucf(i18n("successfully changed password for"))." ".$user->getName();
					}
				}
				
				$this->stage = 0;
				return true;
		
		}
		
		if (empty($stdin))
			$this->user_node_id = $_SESSION['murrix']['user']->getNodeId();
		else
		{
			$user = fetch("FETCH node WHERE property:class_name='user' AND var:username='$stdin' NODESORTBY property:version");
			
			if (count($user) == 0)
			{
				$stderr = ucf(i18n("No such user"));
				return true;
			}
			
			$this->user_node_id = $user[0]->getNodeId();
		}
		
		$stdout = ucf(i18n("enter new password:"));
		$this->stage = 1;
		return false;
	}
}

?>