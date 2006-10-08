<?

function createClass($name, $icon)
{
	$table = new mTable("classes");
	
	$classes = $table->get("`name`='$name'");
	
	if (count($classes) > 0)
	{
		return ucf(i18n("class already exists"));
	}
	else if (!empty($name))
	{
		$class = array("name" => $name, "default_icon" => $icon);
		
		if (!$table->insert($class))
			return $table->error;
		else
			return true;
	}
	
	return ucf(i18n("empty name is forbidden"));
}
?>