<?
$vars = $object->getVars();
if (!empty($vars))
{
	$data = "";
	foreach ($vars as $var)
	{
		$value = $var->getValue();
		if (!empty($value))
			$data .= "<b>".ucf(str_replace("_", " ", i18n($var->getName(true)))).":</b> <div>".$var->getShow()."</div><br/>";
	}

	if (!empty($data))
	{
	?>
		<div class="main">
			<?=$data?>
		</div>
	<?
	}
}
?>