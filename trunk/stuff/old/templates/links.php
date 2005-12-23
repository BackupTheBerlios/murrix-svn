<?
guiTitel("Manage Links ".Img(guiIcon($object->GetIcon()))." ".$object->name, "");
?>
<table class="simple" cellspacing="0" width="100%">
	<tr>
		<td class="simplemain">
			
			<form action="./" method="post" name="form">
				
				<?/* Required vars */?>
				<input class="hidden" type="hidden" name="action" value="link">
				<input class="hidden" type="hidden" name="subaction" value="new">
				<input class="hidden" type="hidden" name="path" value="<?=$object->GetPath()?>">
				<b>Move to:</b> <div><input type="text" name="movepath" value=""></div><br>
				<b>Copy to:</b> <div><input type="text" name="copypath" value=""></div><br>
				
				<b>Link to:</b>
				<div>
					<input type="text" name="linkpath" value="">
					<select name="linktype">
						<option value="sub">As subfolder</option>
						<option value="partner">As partner</option>
						<option value="child">As child</option>
						<option value="data">As related data</option>
					</select>
				</div><br>
				<?/* ============= */?>
				<input type="submit" value="Save">
			</form>
			
			<?
			$linklist[] = array("&nbsp;", "Name", "Linktype");
			
			$hasmap = array(array("sort", array("type", "created")));
			$objects = mObject::GetRelatedWithRights($object->GetRelatedHash($hasmap));
	
			foreach ($objects as $object2)
				$linklist[] = array("<input name=\"".$object2->id."\" type=\"checkbox\">", "<a href=\"".guiPath($object2->GetPath())."\"><img border=\"0\" align=\"middle\" src=\"".guiIcon($object2->GetIcon())."\"> $object2->name</a>", $linktype);
			?>
			
			<form action="./" method="post">
				<input class="hidden" type="hidden" name="action" value="link">
				<input class="hidden" type="hidden" name="subaction" value="delete">
				<input class="hidden" type="hidden" name="path" value="<?=$object->GetPath()?>">
				<div class="titel">
					<div class="text">
						Links
					</div>
					<div>
						<? guiList($linklist) ?>
					</div>
				</div>
				<input type="submit" value="Delete">
			</form>
		</td>
	</tr>
</table>
	
<script type="text/javascript">
	document.form.movepath.focus();
</script>