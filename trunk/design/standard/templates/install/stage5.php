<div style="background-color: #9191C3; padding: 10px; color: #5B5B7A; margin: 10px; font-size: 26px;">
	Theme
</div>
<table class="invisible" cellspacing="0" style="font-size: 12px; margin: 10px; padding: 10px; padding-top: 0">
	<tr>
		<td style="vertical-align: top; width: 120px; border-right: 1px solid #5B5B7A;">
			<? include(gettpl("install/menu")) ?>
		</td>
		<td align="center">
			Below is a list of avalible themes. Choose one.<br/>
			<br/>
			Default theme<br/>
			<form name="sInstall" id="sInstall" action="javascript:void(null);" onsubmit="Post('install','zone_main', 'sInstall')">
				<input type="hidden" class="hidden" name="action" value="theme">
				<select style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="theme">
				<?
					$folders = GetSubfolders("$abspath/design");
					foreach ($folders as $folder)
						echo "<option ".($folder == "standard" ? "selected" : "")." value=\"$folder\">".ucf($folder)."</option>";
				?>
				</select>
			</form>
			<br/>
			<br/>
			<div style="margin-left: 10px; float: left;">
				<?=cmd("Back", "Exec('install', 'zone_main', Hash('stage', '4'))")?>
			</div>
			<div style="margin-right: 10px; float: right;">
				<?=cmd("Next", "Exec('install', 'zone_main', Hash('stage', '6'))")?>
			</div>
		</td>
	</tr>
</table>