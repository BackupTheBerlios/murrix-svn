<?

if (count($children) > 0)
{
	include(gettpl("pager_start", $object));

	switch ($view_slected)
	{
		case "thumbnailes":
		?>
			<div class="show_item_wrapper">
			<?
				for ($i = $start; $i < $end; $i++)
				{
					$child = $children[$i];
					include(gettpl("show_item", $child));
				}
				?>
				<div class="clear"></div>
			</div>
			<?
			break;

		case "table":
			$list = array();
			$list[] = array(ucf(i18n("name")), ucf(i18n("description")), "&nbsp;");
			for ($i = $start; $i < $end; $i++)
			{
				$child = $children[$i];

				$read_right = $child->hasRight("read");
				if ($read_right)
					$name = cmd(img(geticon($child->getIcon()))." ".$child->getName(), "Exec('show','zone_main',Hash('node_id','".$child->getNodeId()."'))");
				else
					$name = img(geticon($child->getIcon()))." ".$child->getName();

				$description = $read_right ? $child->getVarValue("description") : "";

				$admin = "";
				if ($child->hasRight("write"))
				{
					$admin .= cmd(img(geticon("edit")), "Exec('edit','zone_main',Hash('node_id','".$child->getNodeId()."'))");
					$admin .= "&nbsp;";
					$admin .= cmd(img(geticon("delete")), "Exec('delete','zone_main',Hash('node_id','".$child->getNodeId()."'))");
				}
				
				$list[] = array($name, $description, $admin);
			}

			table($list, "% ".i18n("rows"));
			break;
	
		case "list":
		default:
			for ($i = $start; $i < $end; $i++)
			{
				$child = $children[$i];
				include(gettpl("show_line", $child));
			}
			break;
	}

	include(gettpl("pager_end", $object));
}
?>
