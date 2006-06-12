<?

$vars = $object->getVars();
if (!empty($vars))
{
	$vars_flag = false;

	foreach ($vars as $var)
	{
		$value = $var->getValue();
		if (!empty($value))
		{
			if (!$vars_flag)
			{
				?><div class="main"><?
				$vars_flag = true;
			}

			echo "<b>".ucf(str_replace("_", " ", i18n($var->getName(true)))).":</b> <div>".$var->getShow()."</div><br/>";
		}
	}

	if ($vars_flag)
	{
		?></div><?
	}
}

?>