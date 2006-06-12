<?

class csPasswd extends CScript
{
	function csPasswd()
	{
		$this->stage = 0;
	}
	
	function exec($stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!(($user_node_id == $_SESSION['murrix']['user']->id || isAdmin()) && !isAnonymous()))
		{
			$stderr = ucf(i18n("not enough rights to change password for user"));
			return true;
		}
		
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
						$user = new mUser($this->user_node_id);
						$stdout = ucf(i18n("successfully changed password for"))." ".$user->name;
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