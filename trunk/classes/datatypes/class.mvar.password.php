<?

class mVarPassword extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/password", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>