<?

class mTable
{
	var $table;
	var $error;

	function mTable($table)
	{
		$this->table = $table;
		$this->error = "";
	}
		
	function get($where = "")
	{
		global $db_prefix;
		
		if (!empty($where))
			$where = "WHERE $where";
		
		$query = "SELECT * FROM `".$db_prefix.$this->table."` $where";
		
		if (!($result = mysql_query($query)))
		{
			$this->error = "mTable::getList: " . mysql_errno() . " " . mysql_error();
			return false;
		}

		$list = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$list[] = $row;
			
		return $list;
	}
	
	function insert($data)
	{
		global $db_prefix;
		
		$names = "";
		$values = "";
		foreach ($data as $key => $value)
		{
			if (!empty($names))
				$names .= ", ";
				
			$names .= "`$key`";
			
			if (!empty($values))
				$values .= ", ";
				
			$values .= "'$value'";
		}

		$query = "INSERT INTO `".$db_prefix.$this->table."` ($names) VALUES($values)";

		$result = mysql_query($query);
		if (!$result)
		{
			$this->error = "mTable::insert: " . mysql_errno() . " " . mysql_error();
			return false;
		}

		return mysql_insert_id();
	}
	
	function update($id, $newdata)
	{
		global $db_prefix;
		
		$set = "";
		foreach ($newdata as $key => $value)
		{
			if (!empty($set))
				$set .= ", ";
				
			$set .= "`$key`='$value'";
		}

		$query = "UPDATE `".$db_prefix.$this->table."` SET $set WHERE `id` = '$id'";

		$result = mysql_query($query);
		if (!$result)
		{
			$this->error = "mTable::update: " . mysql_errno() . " " . mysql_error();
			return false;
		}
		
		return true;
	}
	
	function updateSingle($id, $key, $value)
	{
		global $db_prefix;
		
		$query = "UPDATE `".$db_prefix.$this->table."` SET `$key`='$value' WHERE `id` = '$id'";

		$result = mysql_query($query);
		if (!$result)
		{
			$this->error = "mTable::update: " . mysql_errno() . " " . mysql_error();
			return false;
		}
		
		return true;
	}
	
	function remove($id)
	{
		global $db_prefix;
			
		$query = "DELETE FROM `".$db_prefix.$this->table."` WHERE `id` = '$id'";
		
		$result = mysql_query($query);
		if (!$result)
		{
			$this->error = "mTable::remove: " . mysql_errno() . " " . mysql_error();
			return false;
		}
		
		return true;
	}
}

?>
