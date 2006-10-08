<?
$text = "";
$current = "";

if (($object->hasRight("read") && ($object->getMeta("show_versionstab", 0) == 1 || $object->getMeta("show_linkstab", 0) == 1)) || $object->hasRight("edit"))
{
	$titel = img(geticon("search"))."&nbsp;".ucf(i18n("view"));
	
	if ($args['view'] == "show")
	{
		$current = $titel;
		$class = "tab_selected";
	}
	else
		$class = "tab";
		
	$text .= cmd($titel, "exec=show&node_id=".$object->getNodeId(), array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));
}

if ($object->hasRight("edit"))
{
	$titel = img(geticon("edit"))."&nbsp;".ucf(i18n("edit"));
	
	if ($args['view'] == "edit")
	{
		$current = $titel;
		$class = "tab_selected";
	}
	else
		$class = "tab";
		
	$text .= cmd($titel, "exec=edit&node_id=".$object->getNodeId(), array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));

	
	$titel = img(geticon("settings"))."&nbsp;".ucf(i18n("settings"));
	
	if ($args['view'] == "settings")
	{
		$current = $titel;
		$class = "tab_selected";
	}
	else
		$class = "tab";
		
	$text .= cmd($titel, "exec=settings&node_id=".$object->getNodeId(), array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));
}

if ($object->hasRight("read"))
{
	if ($object->getMeta("show_versionstab", 0) == 1 || $object->hasRight("write"))
	{
		$titel = img(geticon("list"))."&nbsp;".ucf(i18n("versions"));
		
		if ($args['view'] == "versions")
		{
			$current = $titel;
			$class = "tab_selected";
		}
		else
			$class = "tab";
			
		$text .= cmd($titel, "exec=versions&node_id=".$object->getNodeId(), array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));
	}
		

	if ($object->getMeta("show_linkstab", 0) == 1 || $object->hasRight("write"))
	{
		$titel = img(geticon("link"))."&nbsp;".ucf(i18n("links"));
		
		if ($args['view'] == "links")
		{
			$current = $titel;
			$class = "tab_selected";
		}
		else
			$class = "tab";
			
		$text .= cmd($titel, "exec=links&node_id=".$object->getNodeId(), array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));
	}
}

if ($object->hasRight("write"))
{
	$titel = img(geticon("settings"))."&nbsp;".ucf(i18n("tools"));
	
	if ($args['view'] == "tools")
	{
		$current = $titel;
		$class = "tab_selected";
	}
	else
		$class = "tab";
		
	$text .= cmd($titel, "exec=tools&node_id=".$object->getNodeId(), array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));


	$titel = img(geticon("delete"))."&nbsp;".ucf(i18n("delete"));
	
	if ($args['view'] == "delete")
	{
		$current = $titel;
		$class = "tab_selected";
	}
	else
		$class = "tab";
		
	$text .= cmd($titel, "exec=delete&node_id=".$object->getNodeId(), array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));
}

if ($object->hasRight("create"))
{
	$titel = img(geticon("file"))."&nbsp;".ucf(i18n("new"));
	
	if ($args['view'] == "new")
	{
		$current = $titel;
		$class = "tab_selected";
	}
	else
		$class = "tab";
		
	$text .= cmd($titel, "exec=new&node_id=".$object->getNodeId(), array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));


	$titel = img(geticon("attach"))."&nbsp;".ucf(i18n("upload"));
	
	if ($args['view'] == "upload")
	{
		$current = $titel;
		$class = "tab_selected";
	}
	else
		$class = "tab";
		
	$text .= cmd($titel, "exec=upload&node_id=".$object->getNodeId(), array("onmouseup"=>"document.getElementById('adminpanel').style.display='none'", "class"=>$class));
}

if (!empty($text))
{
?>
	<div class="adminpanel_wrapper">
		<div class="adminpanel_button_wrapper">
			<div class="adminpanel_button"  onmouseover="document.getElementById('adminpanel').style.display='block'">
				<?=$current?>
			</div>
		</div>
		<div id="adminpanel" class="adminpanel" onmouseover="document.getElementById('adminpanel').style.display='block'" onmouseout="document.getElementById('adminpanel').style.display='none'">
			<?=$text?>
		</div>
		<div class="clear"></div>
	</div>
	
<?
}
?>