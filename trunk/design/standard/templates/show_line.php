<table id="line" cellspacing="0">
	<tr>
		<td rowspan="2">
			<div id="main">
				<?
				echo "<a class=\"titel\" class=\"title\" onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$child->GetPath()."'));\" href=\"javascript:void(null);\">".Img(geticon($child->GetIcon(), 64))."</a>";
				?>
			</div>
		</td>
		<td id="left">
			<div id="main">
				<?
				echo "<a class=\"titel\" class=\"title\" onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$child->GetPath()."'));\" href=\"javascript:void(null);\">".$child->name."</a><br/>";
				?>
			</div>
		</td>
		<td id="right">
			<div id="main">
				<?
				$admin = "";

				if (HasRight("edit", $child->GetPath()))
				{
					$admin .= "<a onclick=\"SystemRunScript('edit_object','zone_main', Hash('path', '".$child->GetPath()."'));\" href=\"javascript:void(null);\">";
					$admin .= Img(geticon("edit"));
					$admin .= "</a>";
				}
				
				if (HasRight("delete", $child->GetPath()))
				{
					$admin .= "&nbsp;";
					$admin .= "<a onclick=\"SystemRunScript('delete_object','zone_main', Hash('path', '".$child->GetPath()."'));\" href=\"javascript:void(null);\">";
					$admin .= Img(geticon("delete"));
					$admin .= "</a>";
				}
				
				echo $admin;
				?>
				
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" id="left" width="100%">
			<hr size="1" color="#FCE464" width="99%">
			<div id="main">
				<?=$child->GetValue("description")?>
			</div>
		</td>
	</tr>
	
</table>
