<?

class mUser extends mTable
{
	var $id;
	var $name;
	var $username;
	var $password;
	var $home_id;
	var $groups;
	var $last_login;

	function mUser($id = 0)
	{
		parent::mTable("users");
		
		if ($id > 0)
		{
			$group = $this->get("`id`='$id'");
			
			if (count($group) > 0)
				$this->setByArray($group[0]);
		}
	}
	
	function getList()
	{
		$users = $this->get();
		
		$list = array();
		foreach ($users as $user)
		{
			$u = new mUser();
			$u->setByArray($user);
			$list[] = $u;
		}
		
		return $list;
	}
	
	function sendMessage($subject, $text, $attachment)
	{
		if ($this->id <= 0)
			return false;
			
		$user_id = $_SESSION['murrix']['user']->id;
		$_SESSION['murrix']['user']->id = $this->id;
		
		$home = new mObject($this->home_id);
		
		$inbox_id = getNode($home->getPath()."/inbox", "eng");
		
		if ($inbox_id < 0)
		{
			$inbox = new mObject();
			$inbox->setClassName("folder");
			$inbox->loadVars();

			$inbox->name = "inbox";
			$inbox->language = $_SESSION['murrix']['language'];

			$inbox_id = $inbox->save();
			
			$inbox->rights = $home->getMeta("initial_rights", $home->getRights());
			
			clearNodeFileCache($home->getNodeId());
			$inbox->linkWithNode($home->getNodeId());
		}
		else
			$inbox = new mObject($inbox_id);
		
		$message = new mObject();
		$message->setClassName("message");
		$message->loadVars();
		
		$message->name = $subject;
		$message->language = $_SESSION['murrix']['language'];
		
		$message->setVarValue("text", $text);
		$message->setVarValue("attachment", $attachment);
		$message->setVarValue("sender", $_SESSION['murrix']['user']->name);

		$message->save();
		
		$message->rights = $inbox->getMeta("initial_rights", $inbox->getRights());
		
		clearNodeFileCache($inbox->getNodeId());
		$message->linkWithNode($inbox->getNodeId());
		
		$_SESSION['murrix']['user']->id = $user_id;
		
		return true;
	}
	
	function setByUsername($username)
	{
		$users = $this->get("`username`='$username'");
		
		if (count($users) == 0)
			return false;
		
		$this->setByArray($users[0]);
		return true;
	}
	
	function setByArray($array)
	{
		$this->id = $array['id'];
		$this->name = $array['name'];
		$this->username = $array['username'];
		$this->password = $array['password'];
		$this->home_id = $array['home_id'];
		$this->groups = $array['groups'];
		$this->last_login = $array['last_login'];
		$this->created = $array['created'];
		$this->email = $array['email'];
	}
	
	function getGroups()
	{
		return explode(" ", $this->groups);
	}
	
	function save()
	{
		$array = array();
		$array['name'] = $this->name;
		$array['username'] = $this->username;
		$array['password'] = $this->password;
		$array['home_id'] = $this->home_id;
		$array['groups'] = $this->groups;
		$array['last_login'] = $this->last_login;
		$array['created'] = $this->created;
		$array['email'] = $this->email;
	
		if ($this->id > 0)
			return $this->update($this->id, $array);
		else
		{
			$this->created = date("Y-m-d H:i:s");
			$array['created'] = $this->created;
			$this->id = $this->insert($array);
		}
			
		return $this->id;
	}
	
	function remove()
	{
		return parent::remove($this->id);
	}
}

?>