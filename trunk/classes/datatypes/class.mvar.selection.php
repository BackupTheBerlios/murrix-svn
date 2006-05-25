<?

class mVarSelection extends mVar
{
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;
	
		$selections = explode(",", $this->extra);
		foreach ($selections as $selection)
		{
			$parts = explode("=", $selection);
			if ($parts[0] == $value)
				return $parts[1];
		}
		return "NULL";
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		$selections = explode(",", $this->extra);
		
		$list = array();
		
		foreach ($selections as $selection)
		{
			$parts = explode("=", $selection);
			
			$list[$parts[1]] = $parts[0];
		}
		
		return compiletpl("datatypes/selection/edit", array_merge($this->getStandardArgs($formname, $var_prefix), array("list"=>$list)));
	}
}

?>