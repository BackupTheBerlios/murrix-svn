<div style="background-color: #9191C3; padding: 10px; color: #5B5B7A; margin: 10px; font-size: 26px;">
	License
</div>
<table class="invisible" cellspacing="0" style="font-size: 12px; margin: 10px; padding: 10px; padding-top: 0">
	<tr>
		<td style="vertical-align: top; width: 120px; border-right: 1px solid #5B5B7A;">
			<? include(gettpl("install/menu")) ?>
		</td>
		<td align="center">
			MURRiX is released under the <a target="top" href="http://www.gnu.org/copyleft/gpl.html">GNU GENERAL PUBLIC LICENSE</a>.<br/><br/>
			License<br/>
			<iframe style="width: 90%; height: 300px;; border: 1px solid #9191C3;" src="<?="$wwwpath/docs/LICENSE.txt"?>"></iframe>
			<br/>
			<br/>
			<div style="margin-left: 10px; float: left;">
				<?=cmd("Back", "Exec('install', 'zone_main', Hash('stage', '1'))")?>
			</div>
			<div style="margin-right: 10px; float: right;">
				<?=cmd("Next", "Exec('install', 'zone_main', Hash('stage', '3'))")?>
			</div>
		</td>
	</tr>
</table>