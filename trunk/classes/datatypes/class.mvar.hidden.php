<?

class mVarHidden extends mVar
{
	function getEdit($formname, $var_prefix = "")
	{
		$parts = explode(":", $this->extra);

		switch ($parts[0])
		{
		case "user":
			$value = $_SESSION['murrix']['user']->{$parts[1]}; // ? IS THIS CORRECT??!?!?!
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
		
		return compiletpl("datatypes/standard/edit", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>