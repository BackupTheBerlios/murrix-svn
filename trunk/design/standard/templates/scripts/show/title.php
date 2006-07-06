<?
echo compiletpl("scripts/show/tabs", array("view"=>"show"), $object);

$title_args = array();
$title_args["left"] = img(geticon($object->getIcon()))."&nbsp;".$object->getName();
$time = strtotime($object->getCreated());
$title_args["right"] = date("d ", $time).ucf(i18n(strtolower(date("F", $time)))).date(" Y H:i", $time);
echo compiletpl("title/big", $title_args, $object);
?>