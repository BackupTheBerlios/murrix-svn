<?

class mVarXhtml extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/xhtml/edit", $this->getStandardArgs($formname, $var_prefix));
	}
	
	function getJavaScript($formname, $var_prefix = "")
	{
		return "addEditor('$formname', '{$var_prefix}v".$this->id."');";
	}
}

?>