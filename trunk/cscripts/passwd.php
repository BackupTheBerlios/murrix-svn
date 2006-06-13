<?

class csPasswd extends CScript
{
	function csPasswd()
	{
		$this->stage = 0;
	}
	
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		switch ($this->stage)
		{
			case 1:
				$this->password1 = $args;
				$stdout = ucf(i18n("enter new password again:"));
				$response->addAssign("cmdline","type","password");
				$this->stage = 2;
				return false;
				
			case 2:
				if ($this->password1 != $args)
					$stdout = ucf(i18n("passwords do not match, aborting"));
				else
				{
					$ret = changePassword($this->user_node_id, $args);
					if ($ret !== true)
					{
						$stderr = $ret;
						$this->stage = 0;
						return true;
					}
					else
					{
						$user = new mUser($this->user_node_id);
						$stdout = ucf(i18n("successfully changed password for"))." ".$user->name;
					}
				}
				
				$this->stage = 0;
				return true;
		
		}
		
		if (empty($args))
			$this->user_node_id = $_SESSION['murrix']['user']->id;
		else
		{
			$user = new mUser();
			
			if (!$user->setByUsername($args))
			{
				$stderr = ucf(i18n("No such user"));
				return true;
			}
			
			if (!(($user_node_id == $_SESSION['murrix']['user']->id || isAdmin()) && !isAnonymous()))
			{
				$stderr = ucf(i18n("not enough rights to change password for user"));
				return true;
			}
			
			$this->user_node_id = $user->id;
		}
		
		$stdout = ucf(i18n("enter new password:"));
		$response->addAssign("cmdline","type","password");
		$this->stage = 1;
		return false;
	}
}

?>