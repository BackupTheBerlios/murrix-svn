<div class="main_title">
	Datebase
</div>
<table class="invisible" cellspacing="0">
	<tr>
		<td>
			<? include(gettpl("install/menu")) ?>
		</td>
		<td width="100%">
			<div class="main">
				You have to specify how MURRiX will access MySQL.<br/>
				<br/>
				<form name="sInstall" id="sInstall" action="javascript:void(null);" onsubmit="Post('install','zone_main', 'sInstall')">
					Address to MySQL-server<br/>
					<input class="textline" name="db_address" value="<?=$this->db_address?>" type="text"><br/>
					<br/>
					Name of database<br/>
					<input class="textline" name="db_name" value="<?=$this->db_name?>" type="text"><br/>
					Table prefix<br/>
					<input class="textline" name="db_prefix" value="<?=$this->db_prefix?>" type="text"><br/>
					<br/>
					Username<br/>
					<input class="textline" name="db_username" value="<?=$this->db_username?>" type="text"><br/>
					Password<br/>
					<input class="textline" name="db_password" value="<?=$this->db_password?>" type="password"><br/>
					<input class="hidden" type="hidden" name="stage" value="5">
				</form>
			</div>
			<div class="main_nav">
				<?=cmd("<-- Back", "Exec('install','zone_main',Hash('stage','3'))")?>
				|
				<?=cmd("Next -->", "Post('install','zone_main','sInstall')")?>
			</div>
		</td>
	</tr>
</table>