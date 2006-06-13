<div class="main_title">
	Database Checks
</div>
<table class="invisible" cellspacing="0">
	<tr>
		<td>
			<? include(gettpl("install/menu")) ?>
		</td>
		<td width="100%">
			<div class="main">
				<div class="warning">
					WARNING!<br/>
					Any, conflicting, existing tables will be dropped.<br/>
					Be sure to backup existing data.
				</div>
				<br/>
				Below is a results of the database tests.
				<br/>
				<br/>
				<strong>Server login:</strong> <?=($this->db_login ? "<span style=\"font-weight: bold; color: green;\">Successfull</span>" : "<span style=\"font-weight: bold; color: red;\">Failed</span>")?><br/>
				<strong>Database exists:</strong> <?=($this->db_exists ? "True" : "False")?><br/>
				<strong>All or some tables exits:</strong> <?=($this->db_tables ? "True" : "False")?><br/>
				<br/>
				<strong>Logmessage:</strong><br/>
				<?=$this->db_log?>
				<br/>
				<?=cmd("Run test again", "Exec('install', 'zone_main',Hash('stage','5'))")?>
			</div>
			<div class="main_nav">
				<?=cmd("<-- Back", "Exec('install','zone_main',Hash('stage','4'))")?>
			
				<?
				if($this->db_login)
				{
					echo "| ";
					echo cmd("Next -->", "Exec('install','zone_main',Hash('stage','6'))");
				}
				?>
			</div>
		</td>
	</tr>
</table>