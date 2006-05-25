<?

class mVarThumbnailid extends mVar
{
	function Save()
	{
		$thumbnail = new mThumbnail();
		$thumbnail->Save();
		$this->value = $thumbnail->id;
	
		return parent::Save();
	}
	
	function Remove()
	{
		$value = $this->getValue(true);
		if (!empty($value))
		{
			$thumbnail = new mThumbnail($value);
			$thumbnail->Remove();
		}
		return parent::Remove();
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/hidden/edit", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>