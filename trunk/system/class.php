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

function guessObjectType($object)
{
	$table = new mTable("initial_meta");
	
	$initial_meta = $table->get("`class_name`='".$object->getClassName()."'");
	
	foreach ($initial_meta as $im)
		$object->setMeta($im['name'], $im['value']);
}

function setInitialMetadata($class_name, $name, $value)
{
	$table = new mTable("initial_meta");
	
	$meta = $table->get("`class_name`='$class_name' AND `name`='$name'");
	
	if (count($meta) > 0)
	{
		if (empty($value))
		{
			if (!$table->remove($meta[0]['id']))
				return $table->error;
			else
				return true;
		}
		else
		{
			if (!$table->update($meta[0]['id'], array($name => $value)))
				return $table->error;
			else
				return true;
		}
	}
	else if (!empty($name))
	{
		if (empty($value))
			return ucf(i18n("empty value, nothing set"));
		
		$meta = array("class_name" => $class_name, "name" => $name, "value" => $value);
		
		if (!$table->insert($meta))
			return $table->error;
		else
			return true;
	}
	
	return ucf(i18n("empty name is forbidden"));
}

function getInitialMetadata()
{
	$table = new mTable("initial_meta");
	
	return $table->get();
}

?>