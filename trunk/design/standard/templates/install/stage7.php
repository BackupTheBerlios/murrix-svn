<div class="main_title">
	Finish
</div>
<table class="invisible" cellspacing="0">
	<tr>
		<td>
			<? include(gettpl("install/menu")) ?>
		</td>
		<td width="100%">
			<div class="main">
				<?=($this->done ? "<span style=\"font-weight: bold; color: green;\">Installation completed successfully</span>" : "<span style=\"font-weight: bold; color: red;\">Installation failed. Se below for errors.</span>")?>
				<br/>
				<br/>
				<strong>Logmessage:</strong><br/>
				<?=$this->db_log?>
			</div>
			<div class="main_nav">
				<?=cmd("<-- Back", "Exec('install','zone_main', Hash('stage','6'))")?>
				|
				<a href="./">To MURRiX --></a>
			</div>
		</td>
	</tr>
</table>