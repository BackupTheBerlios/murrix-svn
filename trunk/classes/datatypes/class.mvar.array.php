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
		return compiletpl("datatypes/array", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>