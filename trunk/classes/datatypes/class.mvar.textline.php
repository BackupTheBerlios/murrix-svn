<?

class mVarTextline extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/standard", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>