<?
echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

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
	$versionlist[] = array(ucf(i18n("version")), ucf(i18n("created")), ucf(i18n("class")), ucf(i18n("name")), ucf(i18n("author")), "&nbsp;");
	foreach ($versions as $version)
	{
		if ($version->getCreator() == 0)
			$creator = ucf(i18n("unknown"));
		else
		{
			$creator_obj = new mObject($version->getCreator());
			$creator = cmd($creator_obj->getName(), "Exec('show','zone_main', Hash('path', '".$creator_obj->getPath()."'))");
		}

		if ($object->hasRight("edit"))
			$edit = cmd(img(geticon("edit"))."&nbsp;".ucf(i18n("new version from here")), "Exec('edit','zone_main', Hash('action', 'editversion', 'object_id', '".$version->getId()."'))");
		else
			$edit = "";

		if ($object->hasRight("delete"))
		{
			if ($num_versions == 1)
				$delete = cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "Exec('delete','zone_main', Hash('path', '".$version->getPath()."'))");
			else
				$delete = cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "Exec('versions','zone_main', Hash('action', 'deleteversion', 'object_id', '".$version->getId()."'))");
		}
		else
			$delete = "";
		
		$versionlist[] = array($version->getVersion(), $version->getCreated(),  $version->getClassName(), $version->getName(), $creator, "$delete $edit");
	}
	
	table($versionlist, "% ".i18n("rows"));
}

?>