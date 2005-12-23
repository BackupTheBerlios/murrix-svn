<?

function DrawObjectManager($object)
{
	DrawVersionList($object);
	DrawLinkList($object);
	/*<a onclick="SystemRunScript('objectmanager','zone_main', Hash('action', 'newsubnode', 'node_id', '<?=$node_id?>', 'class_name', 'folder'));" href="javascript:void(null);">Create Subobject</a>*/
	?>
	<br/>
	
	Create Subobject<br/>
	<form name="createSubobjectForm" id="createSubobjectForm" action="javascript:void(null);" onsubmit="SystemRunScriptForm('objectmanager','zone_main', 'createSubobjectForm');">
		<input class="hidden" type="hidden" name="action" value="newsubnode"/>
		<input class="hidden" type="hidden" name="node_id" value="<?=$object->getNodeId()?>"/>
		<select name="class_name">
			<?
			$classlist = getClassList();
			foreach ($classlist as $class_name)
				echo "<option value=\"$class_name\">".ucfirst($class_name)."</option>";
			?>
		</select>
		<input id="submitButton" type="submit" value="Create"/>
	</form>

	<br/>

	<a onclick="SystemRunScript('objectmanager','zone_main', Hash('action', 'deletenode', 'node_id', '<?=$object->getNodeId()?>'));" href="javascript:void(null);"><?=img(geticon("delete"))?> Delete whole node and all it's children</a>
	<?
}

function DrawVersionList($object)
{
	$versions = fetch("FETCH object WHERE property:node_id='".$object->getNodeId()."' SORTBY property:language, property:version, property:name");

	$versionlist[] = array("Language", "Version", "Created", "Class", "Name", "Creator", "Actions");
	foreach ($versions as $version)
	{
		if ($version->getCreator() == 0)
			$creator = "Unknown";
		else
		{
			$creator_obj = new mObject($version->getCreator());
			$creator = "<a onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$creator_obj->getPath."'));\" href=\"javascript:void(null);\">".$creator_obj->getName()."</a>";
		}
	
		$versionlist[] = array($version->getLanguage(), $version->getVersion(), $version->getCreated(),  $version->getClassName(), $version->getName(), $creator, "<a onclick=\"SystemRunScript('objectmanager','zone_main', Hash('action', 'deleteversion', 'object_id', '".$version->getId()."'));\" href=\"javascript:void(null);\">".img(geticon("delete"))." Delete this version</a> | <a onclick=\"SystemRunScript('objectmanager','zone_main', Hash('action', 'editversion', 'object_id', '".$version->getId()."'));\" href=\"javascript:void(null);\">".img(geticon("edit"))." New version from here</a>");
	}
	?>
	<div class="titel">
		<div class="text">
			Versions
		</div>
		<div>
			<? guiList($versionlist) ?>
		</div>
	</div>
	<?
}

function DrawLinkList($object)
{
	$links = $object->getLinks();

	$linklist[] = array("Type", "Remote Node", "Remote node is on...", "Actions");
	foreach ($links as $link)
	{
		if ($link['remote_id'] == 0)
			$remote = "Unknown";
		else
		{
			$remote_obj = new mObject($link['remote_id']);
			$remote = "<a onclick=\"SystemRunScript('show','zone_main', Hash('path', '".$remote_obj->getPath()."'));\" href=\"javascript:void(null);\">".$remote_obj->getName()."</a>";
		}
	
		$linklist[] = array($link['type'], $remote, $link['direction'], "<a onclick=\"SystemRunScript('objectmanager','zone_main', Hash('action', 'deletelink', 'node_id', '$node_id', 'remote_id', '".$link['remote_id']."', 'type', '".$link['type']."'));\" href=\"javascript:void(null);\">".img(geticon("delete"))." Delete</a>");
	}
	?>
	<div class="titel">
		<div class="text">
			Links
		</div>
		<div>
			<? guiList($linklist) ?>
			<br/>
			<a onclick="SystemRunScript('objectmanager','zone_main', Hash('action', 'createlink', 'node_id', '<?=$object->getNodeId()?>'));" href="javascript:void(null);">Create new link</a>
		</div>
	</div>
	<?
}

function DrawCreateLink()
{
	guiTitel("Link With...", "&nbsp;");
	?>
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
				You can only create links where the current node is on the bottom. If you wish to create a link where this object is at the top, then go to the remote object and create the link from there.
				<br/>
				<br/>
				<form name="objectmanagerForm" id="objectmanagerForm" action="javascript:void(null);" onsubmit="sObjectmanagerCall();">
					<input type="text" name="remotepath" value="">
					<select name="type">
						<option value="sub">As subfolder</option>
						<option value="partner">As partner</option>
						<option value="child">As child</option>
						<option value="data">As related data</option>
					</select>
					<input id="submitButton" type="submit" value="Create link"/>
				</form>
			</td>
		</tr>
	</table>
	<?
}

function DrawObjectEdit($object)
{
	$vars = $object->GetVars();

	guiTitel("Edit Object Version", "&nbsp;");
	?>
	<table class="simple" cellspacing="0" width="100%">
		<tr>
			<td class="simplemain">
				<form name="objectmanagerForm" id="objectmanagerForm" action="javascript:void(null);" onsubmit="sObjectmanagerCall();">
					<div>Name:</div> <div><input type="text" name="name" value="<?=$object->getName()?>"></div>
					<br/>
					<div>Icon:</div> <div><input type="text" name="icon" value="<?=$object->getIcon()?>"></div>
					<br/>
					<div>Language:</div> <div>
					<select name="language">
						<option value="eng" <?=($object->getLanguage() == "eng" ? "selected" : "")?>>English</option>
						<option value="swe" <?=($object->getLanguage() == "swe" ? "selected" : "")?>>Swedish</option>
					</select>
					</div>
					<br/>
					<?
					if (isset($object->vars))
					{
						foreach ($object->vars as $var)
							echo "<div>".$var->getName().": (".$var->getType().")</div><div>".$var->getEdit("objectmanagerForm")."</div><br/>";
					}
					?>
					<input id="submitButton" type="submit" value="Save"/>
				</form>
			</td>
		</tr>
	</table>
	<?
}
?>