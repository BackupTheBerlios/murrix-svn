<?

class mVarUrl extends mVar
{
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;
			
		return $value;
	}
	
	function getShow()
	{
		return compiletpl("datatypes/url/show", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>