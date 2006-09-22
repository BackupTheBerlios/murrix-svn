<?
echo compiletpl("scripts/show/tabs", array("view"=>"delete"), $object);
echo compiletpl("title/big", array("left"=>img(geticon($object->getIcon()))."&nbsp;".$object->getName()), $object);
?>
<div class="main">
	<center>
		<?="<span style=\"font-weight: bold; font-size: 16px;\">".ucf(i18n("are you sure you want to delete"))." \"".$object->getName()."\"?</span>"?>
		<br/>
		<table class="invisible" width="50%">
			<tr>
				<td align="center">
					<?=cmd(img(geticon("yes", 32))."<br/>".ucf(i18n("yes")), "exec=delete&action=delete&node_id=".$object->getNodeId())?>
				</td>
				<td align="center">
					<?=cmd(img(geticon("no", 32))."<br/>".ucf(i18n("no")), "exec=show&node_id=".$object->getNodeId())?>
				</td>
			</tr>
		</table>
	</center>
</div>
