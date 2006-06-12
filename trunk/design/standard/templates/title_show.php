<?

$current_view = "show";
include(gettpl("adminpanel", $object));

$center = "";
$left = img(geticon($object->getIcon()))."&nbsp;".$object->getName();
$right = "";

$right .= date("Y-m-d H:i", strtotime($object->getCreated())).$view_form;
include(gettpl("big_title", $object));

?>