<?
$size = "width: 160px; height: 160px;";
$img = cmd(img(geticon($child->getIcon(), 128)), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))", "titel");
?>
<div style="text-align: center; float: left; padding: 5px; <?=$size?>">
	<table class="invisible" cellspacing="0" width="100%" height="100%">
		<tr>
			<td valign="center">
				<?=$img?>
				<br/>
				<?=cmd($child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))", "titel")?>
			</td>
		</tr>
	</table>
</div>