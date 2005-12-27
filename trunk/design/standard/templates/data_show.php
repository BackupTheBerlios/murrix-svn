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
				?>
				<div class="main_bg" style="margin-top: 5px">
				<div class="main">
				<?
				$vars_flag = true;
			}

			if (is_array($value))
			{
				echo "<b>".$var->getName().":</b> <div>";
				foreach ($value as $line)
					echo $line."<br/>";
				echo "</div><br/>";
			}
			else
				echo "<b>".$var->getName().":</b> <div>$value</div><br/>";
		}
	}

	if ($vars_flag)
	{
		?>
		</div>
		</div>
		<?
	}
}

?>