<div style="background-color: #9191C3; padding: 10px; color: #5B5B7A; margin: 10px; font-size: 26px;">
	Welcome
</div>
<table class="invisible" cellspacing="0" style="font-size: 12px; margin: 10px; padding: 10px; padding-top: 0">
	<tr>
		<td style="vertical-align: top; width: 120px; border-right: 1px solid #5B5B7A;">
			<? include(gettpl("install/menu")) ?>
		</td>
		<td align="center">
			This will take you through the necessary parts of installing MURRiX.<br/>
			<br/>
			Readme<br/>
			<iframe style="width: 90%; height: 300px; border: 1px solid #9191C3;" src="<?="$wwwpath/README.txt"?>"></iframe>
			<br/>
			<br/>
			<div style="margin-right: 10px; float: right;">
				<?=cmd("Next", "Exec('install', 'zone_main', Hash('stage', '2'))")?>
			</div>
		</td>
	</tr>
</table>