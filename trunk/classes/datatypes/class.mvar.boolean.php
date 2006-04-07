<?

class mVarBoolean extends mVar
{
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;
			
		return (intval($value) ? "true" : "false");
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/boolean", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>