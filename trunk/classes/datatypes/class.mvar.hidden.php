<?

class mVarHidden extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		switch ($this->extra)
		{
		case "name":
			$value = $_SESSION['murrix']['user']->name;
			break;
		case "userid":
			$value = $_SESSION['murrix']['user']->id;
			break;
		case "username":
			$value = $_SESSION['murrix']['user']->username;
			break;
		case "date":
			$value = date("Y-m-d");
			break;
		case "datetime":
			$value = date("Y-m-d H:i:s");
			break;
		default:
			$value = $this->getValue(true);
			break;
		}
		
		return compiletpl("datatypes/hidden/edit", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>