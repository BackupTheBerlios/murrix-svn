<div style="background-color: #9191C3; padding: 10px; color: #5B5B7A; margin: 10px; font-size: 26px;">
	Theme
</div>
<table class="invisible" cellspacing="0" style="font-size: 12px; margin: 10px; padding: 10px; padding-top: 0">
	<tr>
		<td style="vertical-align: top; width: 120px; border-right: 1px solid #5B5B7A;">
			<? include(gettpl("install/menu")) ?>
		</td>
		<td align="center">
			Below is a list of available themes. Please choose one.<br/>
			<br/>
			Default theme<br/>
			<form name="sInstall" id="sInstall" action="javascript:void(null);" onsubmit="Post('install','zone_main', 'sInstall')">
				<select style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="site">
				<?
					$folders = GetSubfolders("$abspath/design");
					foreach ($folders as $folder)
						echo "<option ".($folder == $this->site ? "selected" : "")." value=\"$folder\">".ucf($folder)."</option>";
				?>
				</select>
				<input class="hidden" type="hidden" name="stage" value="7">
			</form>
			<br/>
			<br/>
			<div style="margin-left: 10px; float: left;">
				<?=cmd("Back", "Exec('install', 'zone_main', Hash('stage', '5'))")?>
			</div>
			<div style="margin-right: 10px; float: right;">
				<?=cmd("Finish", "Post('install', 'zone_main', 'sInstall')")?>
			</div>
		</td>
	</tr>
</table>