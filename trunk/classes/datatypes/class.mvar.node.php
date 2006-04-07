<?

class mVarNode extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/node", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>