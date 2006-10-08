<?
$text = "";
$current = "";

$titel = ucf(i18n("month"));
	
if ($args['view'] == "month")
{
	$current = $titel;
	$class = "tab_selected";
}
else
	$class = "tab";
	
$text .= cmd($titel, "exec=calendar&view=month&date=".$args['date'], array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));


$titel = ucf(i18n("week"));
	
if ($args['view'] == "week")
{
	$current = $titel;
	$class = "tab_selected";
}
else
	$class = "tab";
	
$text .= cmd($titel, "exec=calendar&view=week&date=".$args['date'], array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));


$titel = ucf(i18n("day"));
	
if ($args['view'] == "day")
{
	$current = $titel;
	$class = "tab_selected";
}
else
	$class = "tab";
	
$text .= cmd($titel, "exec=calendar&view=day&date=".$args['date'], array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));


if (!empty($text))
{
?>
	<div class="adminpanel_wrapper">
		<div class="adminpanel_button_wrapper">
			<div class="adminpanel_button"  onmouseover="document.getElementById('adminpanel').style.display='block'">
				<?=$current?>
			</div>
		</div>
		<div id="adminpanel" class="adminpanel" onmouseover="document.getElementById('adminpanel').style.display='block'" onmouseout="document.getElementById('adminpanel').style.display='none'">
			<?=$text?>
		</div>
		<div class="clear"></div>
	</div>
	
<?
}
?>