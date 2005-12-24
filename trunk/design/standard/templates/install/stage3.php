
<div style="border: 1px solid #5B5B7A; width: 600px;">
	<div style="background-color: #9191C3; padding: 10px; color: #5B5B7A; margin: 10px; font-size: 26px;">
		Adminaccount
	</div>
	<div style="width: 100%; margin: 10px; font-size: 12px;">
		<? include(gettpl("install/menu")) ?>
		<div style="margin-right: 20px; padding-left: 20px;">
			You have to specify admin username and password.
			<br/>
			If this is not set you will not be able to log in to you new MURRiX installation.<br/>
			<br/>
			<form>
				Username<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="username" value="admin" type="text"><br/>
				<br/>
				Password<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="password1" type="password"><br/>
				Confirm Password<br/>
				<input style="width: 50%; text-align: center; border: 1px solid #9191C3;" name="password2" type="password">
			</form>
		</div>
		<div style="clear: both;">
	</div>
</div>