<?
global $abspath, $wwwpath;

echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

?><div id="clear"></div><?

$right = $center = "";
$left = "<span style=\"font-weight: bold; font-size: 18px;\">".img(geticon("search"))."&nbsp;".ucf(i18n("search"))." - $query_string</span>";
include(gettpl("big_title"));

?>
<div class="main">
	<center>
		<form id="bigSearch" name="bigSearch" action="javascript:void(null);" onsubmit="Post('search', 'zone_main', 'bigSearch')">
			<br/>
			<input style="width: 60%" name="query" class="form" type="text" value="<?=$query_string?>"/>
			<br/>
			<br/>
			<?=ucf(i18n("klass"))?>
			<br/>
			<select class="form" name="class_name">
			<?
				echo "<option value=\"\">".ucf(i18n("all classes"))."</option>";
				$classlist = getClassList();
				foreach ($classlist as $class_name)
				{
					$selected = "";
					if ($class_name == $class)
						$selected = "selected";

					echo "<option $selected value=\"$class_name\">".ucwords(str_replace("_", " ", $class_name))."</option>";
				}
			?>
			</select>
			<br/>
			<br/>
			<input class="submit" type="submit" value="<?=ucfirst(i18n("search"))?>"/>
		</form>
	</center>
</div>
<?

if (count($children) > 0)
{
	foreach ($children as $child)
		include(gettpl("show_line", $child));
}

?>
