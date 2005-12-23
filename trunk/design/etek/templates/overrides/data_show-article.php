<?

$text = "";
$vars = $object->getVars();
if (!empty($vars))
{
	foreach ($vars as $var)
	{
		$value = $var->getValue();
		if (!empty($value))
		{
			if ($var->getName(true) == "text")
			{
				$text = $var->getValue(true);
				continue;
			}
		}
	}
}
?>

<div class="main_bg" style="margin-top: 5px">
	<div class="main">
		<?=$text?>
	</div>
</div>