<div class="menu_title">
	<?=ucf(i18n("logout"))?>
</div>
<div class="menu_login">
	<div class="label">
		<?=img(geticon("user"))." ".$_SESSION['murrix']['user']->name?>
	</div>
	<?
	if (!empty($_SESSION['murrix']['user']->password))
		echo cmd(img(geticon("password"))." ".ucf(i18n("change password")), "exec=console&initcmd=upass")."<br/>";
	?>
	<form id="sLoginForm" action="javascript:void(null);" onsubmit="Post('login','sLoginForm');">
		<div>
			<input class="hidden" type="hidden" name="action" value="logout"/>
			<input id="sLoginSubmit" class="submit" type="submit" value="<?=ucf(i18n("logout"))?>"/>
		</div>
	</form>
</div>
<div class="menu_title">
	<?=ucf(i18n("homefolders"))?>
</div>
<div class="menu_login">
	<?
	$home_id = $_SESSION['murrix']['user']->home_id;
		
	if ($home_id > 0)
	{
		$home = new mObject($home_id);
		echo cmd(img(geticon("home"))."&nbsp;".ucf($home->getName()), "exec=show&node_id=$home_id")."<br/>";
	}
	
	$groups = $_SESSION['murrix']['user']->getGroups();
	
	foreach ($groups as $group_name)
	{
		$group = new mGroup();
		$group->setByName($group_name);
		$home_id = $group->home_id;
		
		if ($home_id > 0)
		{
			$home = new mObject($home_id);
			echo cmd(img(geticon($home->getIcon()))."&nbsp;".ucf($home->getName()), "exec=show&node_id=$home_id")."<br/>";
		}
	}
?>
</div>