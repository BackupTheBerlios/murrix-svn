<?
$view_form = "";
if ($object->hasRight("edit"))
{
	$view_form = " <form name=\"sViewSelect\" id=\"sViewSelect\" action=\"javascript:void(null);\" onsubmit=\"javascript:void(null)\">";
	$view_form .= "<input type=\"hidden\" class=\"hidden\" name=\"meta\" value=\"view\">";
	$view_form .= "<select class=\"select\" onchange=\"Post('show','zone_main', 'sViewSelect');\" name=\"value\">";
	
	$viewlist = array("list" => "", "thumbnailes" => "thumbnailes", "table" => "table");

	foreach ($viewlist as $key => $view)
	{
		$selected = "";
		if ($key == $view_slected)
			$selected = "selected";
	
		$view_form .= "<option $selected value=\"$view\">".ucf(i18n($key))."</option>";
	}
	
	$view_form .= "</select>";
	$view_form .= "</form>";
}

$current_view = "show";
include(gettpl("adminpanel", $object));

$center = "";
$left = img(geticon($object->getIcon()))."&nbsp;".$object->getName();
$right = "";
if ($object->creator == 0)
	$right .= ucf(i18n("unknown"));
else
{
	$creator = new mObject($object->getCreator());

	if (!$creator->hasRight("read"))
		$right .= img(geticon($creator->getIcon()))."&nbsp;".$creator->getName();
	else
		$right .= cmd(img(geticon($creator->getIcon()))."&nbsp;".$creator->getName(), "Exec('show','zone_main', Hash('path', '".$creator->getPathInTree()."'))");
}
$right .= " - ".$object->getCreated().$view_form;
include(gettpl("big_title", $object));

?>