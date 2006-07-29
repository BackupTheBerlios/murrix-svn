<?
$name = $object->getName();
if ($args['noicon'] != true)
	$name = img(geticon($object->getIcon()))."&nbsp;$name";
?>
<a title="<?=$object->getName()?>" href="<?=$object->getVarValue("address")?>"><nobr><?=$name?></nobr></a>