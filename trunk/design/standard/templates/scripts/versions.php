<?
echo compiletpl("scripts/show/tabs", array("view"=>"versions"), $object);
echo compiletpl("title/big", array("left"=>img(geticon("list"))."&nbsp;".ucf(i18n("versions"))), $object);

$num_versions = count($object->getVersionNumbers());

foreach ($_SESSION['murrix']['languages'] as $language)
{
	$versions = fetch("FETCH object WHERE property:node_id='".$object->getNodeId()."' AND property:language='$language' NODESORTBY property:version,property:name");

	echo compiletpl("title/medium", array("left"=>img(imgpath("$language.jpg"))."&nbsp;".ucf(i18n($language))), $object);

	$versionlist = array();
	$versionlist[] = array(ucf(i18n("version")), ucf(i18n("created")), ucf(i18n("class")), ucf(i18n("name")), ucf(i18n("user")), ucf(i18n("rights")), "&nbsp;");
	foreach ($versions as $version)
	{
		$user = $version->getUser();
		if ($user->id == 0)
			$user = ucf(i18n("unknown"));
		else
			$user = $user->name;
			
		$edit = "";
		if ($object->hasRight("write"))
		{
			$edit = cmd(img(geticon("edit"))."&nbsp;".ucf(i18n("new version from here")), "exec=edit&action=editversion&object_id=".$version->getId());

			$edit .= " ";

			if ($num_versions == 1)
				$edit .= cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "exec=delete&node_id=".$version->getNodeId());
			else
				$edit .= cmd(img(geticon("delete"))."&nbsp;".ucf(i18n("delete")), "exec=versions&action=deleteversion&object_id=".$version->getId());
		}
		else
			$delete = "";
		
		$versionlist[] = array($version->getVersion(), $version->getCreated(),  $version->getClassName(), $version->getName(), $user, $version->getRights(), $edit);
	}
	
	echo compiletpl("table", array("list"=>$versionlist, "endstring"=>"% ".i18n("rows")), $object);
}
?>