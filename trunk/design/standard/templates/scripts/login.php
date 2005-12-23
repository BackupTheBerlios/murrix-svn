<?

function DrawLogin()
{
	?>
	<form id="sLoginForm" action="javascript:void(null);" onsubmit="sLoginCall();">
		<?=Img(geticon("user"))?>
		<input type="text" name="username">
		<?=Img(geticon("password"))?>
		<input type="password" name="password">
		<input id="sLoginSubmit" class="submit" type="submit" value="Login">
	</form>
	<?
}

function DrawLogout()
{
	?>
	<form id="sLoginForm" action="javascript:void(null);" onsubmit="sLoginCall();">
		<a class="menubar" onclick="sMenuCall('<?=$_SESSION['murrix']['user']->getPath()?>');" href="javascript:void(null);"><?=Img(geticon("user"))?> <?=$_SESSION['murrix']['user']->getName()?></a>
		<input id="sLoginSubmit" class="submit" type="submit" value="Logout">
	</form>
	<?
}

?>