<?

class mVarDate extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/date/edit", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>