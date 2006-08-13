<?
$name = htmlspecialchars($object->getName());
if ($args['noicon'] != true)
	$name = img(geticon($object->getIcon()))."&nbsp;$name";
?>
	<a class="<?=$args['class']?>" title="<?=htmlspecialchars($object->getName())?>" href="<?=$object->getVarValue("address")?>"><?=$name?></a>