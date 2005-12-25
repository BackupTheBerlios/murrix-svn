<div style="background-color: #9191C3; padding: 10px; color: #5B5B7A; margin: 10px; font-size: 26px;">
	Finish
</div>
<table class="invisible" cellspacing="0" style="font-size: 12px; margin: 10px; padding: 10px; padding-top: 0">
	<tr>
		<td style="vertical-align: top; width: 120px; border-right: 1px solid #5B5B7A;">
			<? include(gettpl("install/menu")) ?>
		</td>
		<td align="center">
			<?=($this->done ? "Installation completed successfully" : "Installation failed. Se below for errors.")?>
			<br/>
			<br/>
			<strong>Logmessage:</strong><br/>
			<?=$this->db_log?>
			<br/>
			<div style="margin-left: 10px; float: left;">
				<?=cmd("Back", "Exec('install', 'zone_main', Hash('stage', '6'))")?>
			</div>
			<div style="margin-right: 10px; float: right;">
				<a href="./">To MURRiX</a>
			</div>
		</td>
	</tr>
</table>