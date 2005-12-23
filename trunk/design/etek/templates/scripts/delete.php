<?

$current_view = "delete";
include(gettpl("adminpanel", $object));

$left = "<span style=\"font-weight: bold; font-size: 18px;\">".img(geticon("delete"))."&nbsp;".ucf(i18n("delete"))."</span>";
$right = $center = "";
include(gettpl("big_title"));

?>
<div class="main_bg" style="margin-top: 5px">
	<div class="main">
		<center>
		<?="<span style=\"font-weight: bold; font-size: 16px;\">".ucf(i18n("are you sure you want to delete"))." \"".$object->getName()."\"?</span>"?>
		<br/>
		<?
		$yes = cmd(img(geticon("yes", 32))."<br/>Yes", "Exec('delete','zone_main', Hash('action', 'delete', 'path', '".$object->getPath()."'))");
		$no = cmd(img(geticon("no", 32))."<br/>No", "Exec('show','zone_main', Hash('path', '".$object->getPath()."'))");
		?>
		<table class="invisible" width="50%">
			<tr>
				<td align="center">
					<?=$yes?>
				</td>
				<td align="center">
					<?=$no?>
				</td>
			</tr>
		</table>
		</center>
	</div>
</div>
