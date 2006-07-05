<?

$current_view = "show";
include(gettpl("adminpanel", $object));

$center = "";
$left = img(geticon($object->getIcon()))."&nbsp;".$object->getName();
$right = "";

 $time = strtotime($object->getCreated());
$right .= date("d ", $time).ucf(i18n(strtolower(date("F", $time)))).date(" Y H:i", $time).$view_form;
include(gettpl("big_title", $object));

?>