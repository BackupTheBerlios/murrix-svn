<?

class mVarThumbnail extends mVar
{
	function Save()
	{
		$data = $this->getValue(true);
		
		if (empty($data))
			return true;

		if (!strpos($data, ":"))
		{
			$thumbnail = new mThumbnail($data);
			$thumbnail->duplicate();
		}
		else
		{
			$names = explode(":", $data);
	
			$parts = SplitFilepath($names[0]);
	
			$thumbnail = new mThumbnail();
			
			$filename = $names[1];
			
			$angle = GetFileAngle($filename);
			
			$maxsizex = (empty($this->extra) ? 150 : $this->extra);
			
			$thumbnail->CreateFromFile($filename, $parts['ext'], $maxsizex, $maxsizex, $angle);
		}
		
		if (!$thumbnail->Save())
			return false;
		
		$this->value = $thumbnail->id;
		
		return parent::Save();
	}
	
	function Remove()
	{
		$value = parent::getValue(true);
		
		$thumbnail = new mThumbnail($value);
		
		$thumbnail->Remove();
	
		return parent::Remove();
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/thumbnail", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>