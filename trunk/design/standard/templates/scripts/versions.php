<?
$current_view = "versions";
include(gettpl("adminpanel", $object));

$left = img(geticon("list"))."&nbsp;".ucw(i18n("versions"));
$right = $center = "";
include(gettpl("big_title"));

$num_versions = count($object->getVersionNumbers());

foreach ($_SESSION['murrix']['languages'] as $language)
{
	$versions = fetch("FETCH object WHERE property:node_id='".$object->getNodeId()."' AND property:language='$language' NODESORTBY property:version,property:name");

	$left = img(imgpath("$language.jpg"))."&nbsp;".ucf(i18n($language));
	$right = $center = "";
	include(gettpl("medium_title"));

	$versionlist = array();
	$versionlist[] = array(ucf(i18n("version")), ucf(i18n("created")), ucf(i18n("class")), ucf(i18n("name")), ucf(i18n("user")), ucf(i18n("group")), ucf(i18n("rights")), "&nbsp;");
	foreach ($versions as $version)
	{
		$user = $version->getUser();
		if ($user->id == 0)
			$user = ucf(i18n("unknown"));
		else
			$user = $user->name;
			
		$group = $version->getGroup();
		if ($group->id == 0)
			$group = ucf(i18n("unknown"));
		else
			$group = $group->name;

		$edit = "";
		if ($object->hasRight("write"))
		{
			$edit = cmd(img(geticon("edit"))."&nbsp;".ucf(i18n("new version from here")), "Exec('edit','zone_main',Hash('action','editversion','object_id','".$version->getId()."'))");

			$edit .= " ";

			if ($num_versions == 1)
				$edit .= cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "Exec('delete','zone_main',Hash('node_id','".$version->getNodeId()."'))");
			else
				$edit .= cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "Exec('versions','zone_main',Hash('action','deleteversion','object_id','".$version->getId()."'))");
		}
		else
			$delete = "";
		
		$versionlist[] = array($version->getVersion(), $version->getCreated(),  $version->getClassName(), $version->getName(), $user, $group, $version->getRights(), $edit);
	}
	
	table($versionlist, "% ".i18n("rows"));
}

?>