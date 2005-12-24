<div style="background-color: #9191C3; padding: 10px; color: #5B5B7A; margin: 10px; font-size: 26px;">
	Datebase
</div>
<table class="invisible" cellspacing="0" style="font-size: 12px; margin: 10px; padding: 10px; padding-top: 0">
	<tr>
		<td style="vertical-align: top; width: 120px; border-right: 1px solid #5B5B7A;">
			<? include(gettpl("install/menu")) ?>
		</td>
		<td align="center">
			You have to specify how MURRiX will access MySQL.<br/>
			<br/>
			<form name="sInstall" id="sInstall" action="javascript:void(null);" onsubmit="Post('install','zone_main', 'sInstall')">
				Address to MySQL-server<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="address" value="localhost" type="text"><br/>
				<br/>
				Name of database<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="name" value="murrix" type="text"><br/>
				Table prefix<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="prefix" value="murrix_" type="text"><br/>
				<br/>
				Username<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="username" type="text"><br/>
				Password<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="password" type="password">
			</form>
			<br/>
			<br/>
			<div style="margin-left: 10px; float: left;">
				<?=cmd("Back", "Exec('install', 'zone_main', Hash('stage', '3'))")?>
			</div>
			<div style="margin-right: 10px; float: right;">
				<?=cmd("Next", "Exec('install', 'zone_main', Hash('stage', '5'))")?>
			</div>
		</td>
	</tr>
</table>