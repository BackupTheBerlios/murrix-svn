<?
$description = $object->getVarShow("description");

if (!empty($description))
{
?>
	<div class="main">
		<div class="container">
			<?=$description?>
		</div>
	</div>
<?
}
?>