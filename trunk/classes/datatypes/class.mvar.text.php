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
	
	function getShow()
	{
		return compiletpl("datatypes/text/show", $this->getStandardArgs($formname, $var_prefix));
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/text/edit", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>