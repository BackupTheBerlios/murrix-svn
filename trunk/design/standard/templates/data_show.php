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

			if (is_array($value))
			{
				echo "<b>".ucf(str_replace("_", " ", i18n($var->getName(true)))).":</b> <div>";
				foreach ($value as $line)
					echo $line."<br/>";
				echo "</div><br/>";
			}
			else if ($var->getType() == "thumbnail")
			{
				$thumbnail = new mThumbnail($value);
				echo "<b>".ucf(str_replace("_", " ", i18n($var->getName(true)))).":</b> <div>";
				$thumbnail->Show();
				echo "</div><br/>";
			}
			else
				echo "<b>".ucf(str_replace("_", " ", i18n($var->getName(true)))).":</b> <div>$value</div><br/>";
		}
	}

	if ($vars_flag)
	{
		?></div><?
	}
}

?>