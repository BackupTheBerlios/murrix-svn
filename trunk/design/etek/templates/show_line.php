<table id="line" cellspacing="0">
	<tr>
		<td rowspan="2">
			<div id="main">
				<?=cmd(img(geticon($child->getIcon(), 64)), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))", "titel")?>
			</div>
		</td>
		<td id="left">
			<div id="main">
				<?=cmd($child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))", "titel")?>
			</div>
		</td>
		<td id="right">
			<div id="main">
				<?
				$admin = "";

				if ($child->hasRight("edit"))
				{
					$admin .= cmd(img(geticon("edit")), "Exec('edit','zone_main', Hash('path', '".$child->getPath()."'))");
				}
				
				if ($child->hasRight("delete"))
				{
					$admin .= "&nbsp;";
					$admin .= cmd(img(geticon("delete")), "Exec('delete','zone_main', Hash('path', '".$child->getPath()."'))");
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
				<?=$child->getVarValue("description")?>
			</div>
		</td>
	</tr>
	
</table>