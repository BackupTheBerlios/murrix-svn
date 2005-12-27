<?

$view_form = "";
if ($object->hasRight("edit"))
{
	$view_form = " <form style=\"display: inline;\" name=\"sViewSelect\" id=\"sViewSelect\" action=\"javascript:void(null);\" onsubmit=\"javascript:void(null)\">";
	$view_form .= "<input type=\"hidden\" class=\"hidden\" name=\"meta\" value=\"view\">";
	$view_form .= "<select class=\"form\" onchange=\"Post('show','zone_main', 'sViewSelect');\" name=\"value\">";
	
	$viewlist = array("list" => "", "thumbnailes" => "thumbnailes");

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
$left = "<span style=\"font-weight: bold; font-size: 18px;\">".img(geticon($object->getIcon()))."&nbsp;".$object->getName()."</span>";
$right = "<strong>";
if ($object->creator == 0)
	$right .= ucf(i18n("unknown"));
else
{
	$creator = new mObject($object->getCreator());

	if (!$creator->hasRight("read"))
		$right .= img(geticon($creator->getIcon()))."&nbsp;".$creator->getName();
	else
		$right .= cmd(img(geticon($creator->getIcon()))."&nbsp;".$creator->getName(), "Exec('show','zone_main', Hash('path', '".$creator->getPath()."'))");
}
$right .= "</strong> - ".$object->getCreated().$view_form;
include(gettpl("big_title", $object));

?>