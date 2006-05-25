<?

class mVarArray extends mVar
{
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;
		
		return array_diff(explode("\n", $value), array(""));
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/array/edit", $this->getStandardArgs($formname, $var_prefix));
	}
	
	function getShow()
	{
		return compiletpl("datatypes/array/show", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>