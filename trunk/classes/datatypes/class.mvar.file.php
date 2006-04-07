<?

class mVarFile extends mVar
{
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;

		if (empty($value))
			return "";
			
		global $abspath;

		$parts = SplitFilepath($value);
		return "$abspath/files/".$this->value_id.".".$parts['extension'];
	}
	
	function Save()
	{
		$data = $this->getValue(true);
		
		if (empty($data))
			return true;

		global $abspath;

		//$data = "filename.txt:/tmp/phpSffowB_tmpfile";

		$names = explode(":", $data);

		$parts = SplitFilepath($names[0]);

		$this->value = $names[0];
		
		if (parent::Save())
		{
			$filename = "$abspath/files/".$this->value_id.".".$parts['extension'];
			if (!copy($names[1], $filename))
			{
				return "Error while moving uploaded file from ".$names[1]." to $filename";
				//return false;
			}
		}
	
		return true;
	}
	
	function Remove()
	{
		@unlink($this->getValue());
		return parent::Remove();
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/file", $this->getStandardArgs($formname, $var_prefix));

	}
}

?>