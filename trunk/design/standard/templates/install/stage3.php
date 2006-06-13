<div class="main_title">
	Accounts
</div>
<table class="invisible" cellspacing="0">
	<tr>
		<td>
			<? include(gettpl("install/menu")) ?>
		</td>
		<td width="100%">
			<div class="main">
				You have to specify admin username and password.
				<br/>
				If this is not set you will not be able to log in to you new MURRiX installation.<br/>
				<br/>
				<form name="sInstall" id="sInstall" action="javascript:void(null);" onsubmit="Post('install','zone_main', 'sInstall')">
					Username<br/>
					<input class="textline" name="admin_username" value="<?=$this->admin_username?>" type="text"><br/>
					<br/>
					Password<br/>
					<input class="textline" name="admin_password1" type="password" value="<?=$this->admin_password?>"><br/>
					Confirm Password<br/>
					<input class="textline" name="admin_password2" type="password" value="<?=$this->admin_password?>"><br/>
					<input class="hidden" type="hidden" name="stage" value="4">
				</form>
			</div>
			<div class="main_nav">
				<?=cmd("<-- Back", "Exec('install','zone_main',Hash('stage','2'))")?>
				|
				<?=cmd("Next -->", "Post('install','zone_main','sInstall')")?>
			</div>
		</td>
	</tr>
</table>