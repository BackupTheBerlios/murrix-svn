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
			$this->user_node_id = $_SESSION['murrix']['user']->id;
		else
		{
			$user = new mUser();
			;
			
			if (!$user->setByUsername($stdin))
			{
				$stderr = ucf(i18n("No such user"));
				return true;
			}
			
			$this->user_node_id = $user->id;
		}
		
		$stdout = ucf(i18n("enter new password:"));
		$this->stage = 1;
		return false;
	}
}

?>