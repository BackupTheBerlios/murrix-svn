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

		$extension = pathinfo($value, PATHINFO_EXTENSION);
		return "$abspath/files/".$this->value_id.".$extension";
	}
	
	function Save()
	{
		$data = $this->getValue(true);
		
		if (empty($data))
			return true;

		global $abspath;

		//$data = "filename.txt:/tmp/phpSffowB_tmpfile";

		$names = explode(":", $data);

		$extension = pathinfo($names[0], PATHINFO_EXTENSION);

		$this->value = $names[0];
		
		if (parent::Save())
		{
			$filename = "$abspath/files/".$this->value_id.".$extension";
			if ($names[1] != $filename)
			{
				if (!copy($names[1], $filename))
				{
					return "Error while moving uploaded file from $data".$names[1]." to $filename";
					//return false;
				}
			}
		}
	
		return true;
	}
	
	function Remove()
	{
		delThumbnails($this->value_id);
		@unlink($this->getValue());
		return parent::Remove();
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		global $abspath;
		
		$args = $this->getStandardArgs($formname, $var_prefix);
		
		$extension = pathinfo($args['value'], PATHINFO_EXTENSION);

		$args['filepath'] = "$abspath/files/".$this->value_id.".$extension";
	
		return compiletpl("datatypes/file/edit", $args);
	}
	
	function getShow()
	{
		return compiletpl("datatypes/file/show", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>