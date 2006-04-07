<?

class mVarXhtml extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/xhtml", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>