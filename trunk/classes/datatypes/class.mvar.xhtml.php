<?

class mVarXhtml extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/xhtml/edit", $this->getStandardArgs($formname, $var_prefix));
	}
	
	function getShow()
	{
		return compiletpl("datatypes/xhtml/show", $this->getStandardArgs($formname, $var_prefix));
	}
	
	function getJavaScript($formname, $var_prefix = "")
	{
		return "__FCKeditorNS = null;FCKeditorAPI = null;";
	}
}

?>