<?
echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

$current_view = "start";
include(gettpl("scripts/admin/adminpanel"));

$left = img(geticon("settings"))."&nbsp;".ucf(i18n("administration"));
$right = $center = "";
include(gettpl("big_title"));

$left = img(geticon("group"))."&nbsp;".ucf(i18n("groups"));
$right = $center = "";
include(gettpl("medium_title"));

$groupslist = array();
$groupslist[] = array(ucf(i18n("name")), ucf(i18n("created")), ucf(i18n("home folder")), ucf(i18n("users")), "&nbsp;");

$users_id = resolvePath("/Root/Users");

$groups = fetch("FETCH node WHERE link:node_top='$users_id' AND link:type='sub' AND property:class_name='group' NODESORTBY property:version SORTBY property:name");

foreach ($groups as $group)
{
	$home = "&nbsp;";
	if (resolvePath("/Root/Home/".$group->getName()) > 0)
		$home = cmd(img(geticon("home"))." ".$group->getName(), "Exec('show', 'zone_main', Hash('path', '/Root/Home/".$group->getName()."'))");
	

	$users_count = fetch("FETCH count WHERE link:node_top='".$group->getNodeId()."' AND link:type='sub' AND property:class_name='user' NODESORTBY property:version");
	$groupslist[] = array(cmd(img(geticon($group->getIcon()))." ".$group->getName(), "Exec('show', 'zone_main', Hash('path', '".$group->getPathInTree()."'))"), $group->getCreated(), $home, $users_count, "");
}

table($groupslist, "% ".i18n("rows"));

$left = img(geticon("user"))."&nbsp;".ucf(i18n("users"));
$right = $center = "";
include(gettpl("medium_title"));

$userslist = array();
$userslist[] = array(ucf(i18n("name")), ucf(i18n("username")), ucf(i18n("created")), "&nbsp;");

$users = fetch("FETCH node WHERE property:class_name='user' NODESORTBY property:version SORTBY property:name");

foreach ($users as $user)
{
	$userslist[] = array(cmd(img(geticon($user->getIcon()))." ".$user->getName(), "Exec('show', 'zone_main', Hash('path', '".$user->getPathInTree()."'))"), $user->getVarValue("username"), $user->getCreated(), "");
}

table($userslist, "% ".i18n("rows"));
?>