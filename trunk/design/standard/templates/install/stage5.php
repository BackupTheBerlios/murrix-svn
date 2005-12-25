<div style="background-color: #9191C3; padding: 10px; color: #5B5B7A; margin: 10px; font-size: 26px;">
	Database Tests
</div>
<table class="invisible" cellspacing="0" style="font-size: 12px; margin: 10px; padding: 10px; padding-top: 0">
	<tr>
		<td style="vertical-align: top; width: 120px; border-right: 1px solid #5B5B7A;">
			<? include(gettpl("install/menu")) ?>
		</td>
		<td align="center">
			Below is a results of the database tests.
			<br/>
			<br/>
			<strong>Server login:</strong> <?=($this->db_login ? "Successfull" : "Failed")?><br/>
			<strong>Database exists:</strong> <?=($this->db_exists ? "True" : "False")?><br/>
			<strong>All or some tables exits:</strong> <?=($this->db_tables ? "True" : "False")?><br/>
			<br/>
			<strong>Logmessage:</strong><br/>
			<?=$this->db_log?>
			<br/>
			<?=cmd("Run test again", "Exec('install', 'zone_main', Hash('stage', '5'))")?>
			<br/>
			<br/>
			<span style="font-weight: bold; color: red;">
				WARNING!<br/>
				Any, conflicting, existing tables will be dropped.
			</span>
			<br/>
			<br/>
			<div style="margin-left: 10px; float: left;">
				<?=cmd("Back", "Exec('install', 'zone_main', Hash('stage', '4'))")?>
			</div>
			<? if($this->db_login) { ?>
			<div style="margin-right: 10px; float: right;">
				<?=cmd("Next", "Exec('install', 'zone_main', Hash('stage', '6'))")?>
			</div>
			<? } ?>
		</td>
	</tr>
</table>