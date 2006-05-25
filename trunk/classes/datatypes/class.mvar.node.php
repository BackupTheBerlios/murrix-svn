<?

class mVarNode extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/node/edit", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>