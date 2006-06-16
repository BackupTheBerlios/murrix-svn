<?

class mGroup extends mTable
{
	var $id;
	var $name;
	var $description;

	function mGroup($id = 0)
	{
		parent::mTable("groups");
		
		if ($id > 0)
		{
			$group = $this->get("`id`='$id'");
			
			if (count($group) > 0)
				$this->setByArray($group[0]);
		}
	}
	
	function getList()
	{
		$groups = $this->get();
		
		$list = array();
		foreach ($groups as $group)
		{
			$g = new mGroup();
			$g->setByArray($group);
			$list[] = $g;
		}
		
		return $list;
	}
	
	function setByName($name)
	{
		$groups = $this->get("`name`='$name'");
		
		if (count($groups) == 0)
			return false;
		
		$this->setByArray($groups[0]);
		return true;
	}
	
	function setByArray($array)
	{
		$this->id = $array['id'];
		$this->name = $array['name'];
		$this->description = $array['description'];
		$this->home_id = $array['home_id'];
		$this->created = $array['created'];
	}
	
	function save()
	{
		$array = array();
		$array['name'] = $this->name;
		$array['description'] = $this->description;
		$array['home_id'] = $this->home_id;
		$array['created'] = $this->created;
	
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