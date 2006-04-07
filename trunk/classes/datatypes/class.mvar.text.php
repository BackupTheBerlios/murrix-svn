<?

class mVarText extends mVar
{
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;
			
		return nl2br($value);
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/text", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>