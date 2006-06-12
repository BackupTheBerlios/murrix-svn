<?

class mUser extends mTable
{
	var $id;
	var $name;
	var $username;
	var $password;
	var $home_id;
	var $groups;

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
	
		if ($this->id > 0)
			return $this->update($this->id, $array);
		else
			$this->id = $this->insert($array);
			
		return $this->id;
	}
	
	function remove()
	{
		return parent::remove($this->id);
	}
}

?>