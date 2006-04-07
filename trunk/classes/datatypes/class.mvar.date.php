<?

class mVarDate extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/date", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>