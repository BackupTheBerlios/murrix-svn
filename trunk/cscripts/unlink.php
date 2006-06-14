<?

class csUnlink extends CScript
{
	function exec($args, $stdin, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($args))
		{
			$object = new mObject(getNode($_SESSION['murrix']['path']));
			
			if (!$object->hasRight("write"))
			{
				$stderr = ucf(i18n("not enough rights"));
				return true;
			}
			
			$links = $object->getLinks();
			$link_matched = false;
			foreach ($links as $link)
			{
				if ($link['id'] == $args)
				{
					$link_matched = $link;
					break;
				}
			}
			
			if ($matched === false)
			{
				$stderr = ucf(i18n("unknown link specified"));
				return true;
			}
			
			$object->deleteLink($args);
			clearNodeFileCache($object->getNodeId());
			clearNodeFileCache($link_matched['remote_id']);

			$_SESSION['murrix']['path'] = $object->getPathInTree();
			$system->TriggerEventIntern($response, "newlocation", array());
			$stdout = ucf(i18n("removed link successfully"));
		}
			
		return true;
	}
}

?>