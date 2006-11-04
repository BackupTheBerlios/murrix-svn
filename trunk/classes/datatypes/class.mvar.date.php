<?

class mVarDate extends mVar
{
	function setValue($value)
	{
		if (!empty($value))
			$value = date("Y-m-d", strtotime($value));
		$this->value = $value;
	}

	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/date/edit", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>