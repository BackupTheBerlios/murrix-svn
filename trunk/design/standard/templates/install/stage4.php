
<div style="border: 1px solid #5B5B7A; width: 600px;">
	<div style="background-color: #9191C3; padding: 10px; color: #5B5B7A; margin: 10px; font-size: 26px;">
		Datebase
	</div>
	<div style="width: 100%; margin: 10px; font-size: 12px;">
		<? include(gettpl("install/menu")) ?>
		<div style="margin-right: 20px; padding-left: 20px;">
			You have to specify how MURRiX will access MySQL.<br/>
			<br/>
			<form>
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
		</div>
		<div style="clear: both;">
	</div>
</div>