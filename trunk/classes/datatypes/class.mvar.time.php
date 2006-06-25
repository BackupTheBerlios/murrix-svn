<?

class mVarTime extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/standard/edit", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>