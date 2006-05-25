<?

require_once("datatypes/list.php");

class mVar
{
	var $id;
	var $class_name;
	var $name;
	var $type;
	var $extra;
	var $comment;
	var $priority;
	var $object_id;
	var $value;
	var $value_id;
	
	function mVar()
	{
		$this->value = "";
		$this->value_id = 0;
	}
	
	function SetByArray($array)
	{
		$this->id = $array['id'];
		$this->class_name = $array['class_name'];
		$this->name = $array['name'];
		$this->type = $array['type'];
		$this->extra = $array['extra'];
		$this->comment = $array['comment'];
		$this->required = $array['required'];
		$this->priority = $array['priority'];
		$this->object_id = $array['object_id'];

		$this->value = isset($array['data']) ? $array['data'] : "";
		$this->value_id = isset($array['value_id']) ? $array['value_id'] : "";
	}
	
	function getName($raw = false)
	{
		if ($raw)
			return $this->name;
		
		return ucf(str_replace("_", " ", $this->name));
	}
	
	function getComment()
	{
		return $this->comment;
	}
	
	function getRequired()
	{
		return $this->required;
	}
	
	function getPriority()
	{
		return $this->priority;
	}
	
	function getExtra()
	{
		return $this->extra;
	}
	
	function getType()
	{
		return $this->type;
	}
	
	function getValue($raw = false)
	{
		if ($raw)
			return $this->value;
			
		$value = htmlspecialchars($this->value);
		
		return $value;
	}

	function setValue($value)
	{
		$this->value = $value;
	}
	
	function getJavaScript($formname, $var_prefix = "")
	{
		return "";
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/standard/edit", $this->getStandardArgs($formname, $var_prefix));
	}
	
	function getShow()
	{
		return compiletpl("datatypes/standard/show", $this->getStandardArgs($formname, $var_prefix));
	}
	
	function getStandardArgs($formname, $var_prefix)
	{
		return array("formname"=>$formname, "varname"=>"{$var_prefix}v".$this->id, "prefix"=>$var_prefix, "id"=>$this->id, "value_id"=>$this->value_id, "value"=>$this->value, "fvalue"=>$this->getValue());
	}
	
	function Save()
	{
		global $db_prefix;

		$data = $this->value;
		
		if ($data == "")
			return $this->Remove();

		$data = str_replace("'", "\'", $data);
	
		if ($this->value_id > 0)
		{
			$query = "UPDATE `".$db_prefix."values` SET data='$data', object_id='$this->object_id', var_id='$this->id' WHERE id = '$this->value_id'";
	
			$result = mysql_query($query);
			if (!$result)
			{
				$message = "<b>An error occured while updateing</b><br/>";
				$message .= "<b>Table:</b> values<br/>";
				$message .= "<b>Query:</b> $query<br/>";
				$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
				$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
				return $message;
			}
		}
		else
		{
			$query = "INSERT INTO `".$db_prefix."values` (data, object_id, var_id) VALUES('$data', '$this->object_id', '$this->id')";
	
			$result = mysql_query($query);
			if (!$result)
			{
				$message = "<b>An error occured while inserting</b><br>";
				$message .= "<b>Table:</b> values<br>";
				$message .= "<b>Query:</b> $query<br>";
				$message .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
				$message .= "<b>Error:</b> " . mysql_error() . "<br>";
				return $message;
			}
			
			$this->value_id = mysql_insert_id();
		}
		
		return true;
	}
	
	function Remove()
	{
		global $db_prefix;
		
		$query = "DELETE FROM `".$db_prefix."values` WHERE object_id = '$this->object_id' AND var_id = '$this->id'";
		$result = mysql_query($query);
		if (!$result)
		{
			$message = "<b>An error occured while deleting</b><br>";
			$message .= "<b>Table:</b> values<br>";
			$message .= "<b>Query:</b> $query<br>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br>";
			echo $message;
			return false;
		}
		return true;
	}

	function getSerialized()
	{
		$array = array();
		
		$array['name'] = $this->name;
		$array['type'] = $this->type;
		$array['extra'] = $this->extra;
		$array['comment'] = $this->comment;
		$array['priority'] = $this->priority;
		$array['value_id'] = $this->value_id;
		$array['value'] = $this->value;
	
		return $array;
	}
}
?>
