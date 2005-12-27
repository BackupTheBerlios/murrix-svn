<div style="background-color: #9191C3; padding: 10px; color: #5B5B7A; margin: 10px; font-size: 26px;">
	Adminaccount
</div>
<table class="invisible" cellspacing="0" style="font-size: 12px; margin: 10px; padding: 10px; padding-top: 0">
	<tr>
		<td style="vertical-align: top; width: 120px; border-right: 1px solid #5B5B7A;">
			<? include(gettpl("install/menu")) ?>
		</td>
		<td align="center">
			You have to specify admin username and password.
			<br/>
			If this is not set you will not be able to log in to you new MURRiX installation.<br/>
			<br/>
			<form name="sInstall" id="sInstall" action="javascript:void(null);" onsubmit="Post('install','zone_main', 'sInstall')">
				Username<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="admin_username" value="<?=$this->admin_username?>" type="text"><br/>
				<br/>
				Password<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="admin_password1" type="password" value="<?=$this->admin_password?>"><br/>
				Confirm Password<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="admin_password2" type="password" value="<?=$this->admin_password?>"><br/>
				<input class="hidden" type="hidden" name="stage" value="4">
			</form>
			<br/>
			<br/>
			<div style="margin-left: 10px; float: left;">
				<?=cmd("Back", "Exec('install', 'zone_main', Hash('stage', '2'))")?>
			</div>
			<div style="margin-right: 10px; float: right;">
				<?=cmd("Next", "Post('install', 'zone_main', 'sInstall')")?>
			</div>
		</td>
	</tr>
</table>