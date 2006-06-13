<div class="main_title">
	Settings
</div>
<table class="invisible" cellspacing="0">
	<tr>
		<td>
			<? include(gettpl("install/menu")) ?>
		</td>
		<td width="100%">
			<div class="main">
				Below is a list of available themes. Please choose one.<br/>
				<br/>
				Default theme<br/>
				<form name="sInstall" id="sInstall" action="javascript:void(null);" onsubmit="Post('install','zone_main', 'sInstall')">
					<select class="selectbox" name="site">
					<?
						$folders = GetSubfolders("$abspath/design");
						foreach ($folders as $folder)
							echo "<option ".($folder == $this->site ? "selected" : "")." value=\"$folder\">".ucf($folder)."</option>";
					?>
					</select>
					<input class="hidden" type="hidden" name="stage" value="7">
				</form>
			</div>
			<div class="main_nav">
				<?=cmd("<-- Back", "Exec('install','zone_main',Hash('stage','5'))")?>
				|
				<?=cmd("Finish -->", "Post('install','zone_main','sInstall')")?>
			</div>
		</td>
	</tr>
</table>