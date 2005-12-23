<?

function DrawMenu($root_id)
{
	?>
	<table class="simple2">
		<tr>
			<td class="simplebig3" colspan="2">
				Menu
			</td>
		</tr>
		<tr>
			<td class="simplemain2">
				<?guiDrawTreeRecursive(new mObject($root_id))?>
			</td>
		</tr>
	</table>
	<?
}

?>