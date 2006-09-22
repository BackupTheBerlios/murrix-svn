<?
echo compiletpl("scripts/show/tabs", array("view"=>"show"), $object);

echo compiletpl("title/big", array("left"=>img(geticon($object->getIcon()))."&nbsp;".$object->getName()), $object);
?>
<div class="poster">
<?
	$time = strtotime($object->getCreated());
	$date = date("d ", $time).ucf(i18n(strtolower(date("F", $time)))).date(" Y H:i", $time);
	$user = $object->getUser();
	echo ucf(i18n("created by"))." $user->name - $date";
?>
</div>