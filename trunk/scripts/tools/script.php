<?

class sTools extends Script
{
	function sTools()
	{
		$this->zone = "zone_main";
	}
	
	function EventHandler(&$system, &$response, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			if ($this->active)
				$this->Draw($system, $response, $args);
			break;
		}
	}

	function Exec(&$system, &$response, $args)
	{
		if (isset($args['action']))
		{
			$object = new mObject($args['parent_id']);
	
			if ($object->hasRight("write"))
			{
				if (count($args['node_ids']) == 0)
				{
					$response->addAlert(ucf(i18n("you must select at least one object")));
					return;
				}
				
				if ($args['action'] == "delete")
				{
					foreach ($args['node_ids'] as $node_id)
					{
						$child = new mObject($node_id);
						
						$not_allowed_str = "";
						if ($child->hasRight("write"))
							$child->deleteNode();
						else
							$not_allowed_str .= $child->getPathInTree()."\n";
							
						if (!empty($not_allowed_str))
							$response->addAlert(ucf(i18n("you did not have enough rights to delete these nodes:"))."\n$not_allowed_str");
					}
					
					clearNodeFileCache($object->getNodeId());
				}
				else
				{
					if (isset($args['remote_node_id']))
						$remote_node_id = $args['remote_node_id'];
					else
						$remote_node_id = getNode($args['path']);
		
					if ($remote_node_id > 0)
					{
						$remote = new mObject($remote_node_id);
		
						if ($remote->hasRight("write"))
						{
							switch ($args['action'])
							{
								case "move":
								foreach ($args['node_ids'] as $node_id)
								{
									$child = new mObject($node_id);
				
									$child->linkWithNode($remote_node_id, "sub");
									$child->unlinkWithNode($object->getNodeId(), "sub", "bottom");
									clearNodeFileCache($object->getNodeId());
									clearNodeFileCache($child->getNodeId());
									clearNodeFileCache($remote_node_id);
								}
								break;
								
								case "link":
								foreach ($args['node_ids'] as $node_id)
								{
									$child = new mObject($node_id);
				
									$child->linkWithNode($remote_node_id, "sub");
									clearNodeFileCache($remote_node_id);
									clearNodeFileCache($child->getNodeId());
								}
								break;
							}
						}
						else
						{
							$response->addAlert(ucf(i18n("you don't have enough rights on the target")));
							return;
						}
					}
					else
					{
						$response->addAlert(ucf(i18n("the remote object you specified does not exist")));
						return;
					}
				}
			}
			else
			{
				$response->addAlert(ucf(i18n("you don't have enough rights")));
				return;
			}
		}

		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		$node_id = $this->getNodeId($args);

		$data = "";
		if ($node_id > 0)
		{
			$object = new mObject($node_id);
			$data = compiletpl("scripts/tools", array(), $object);
		}
		else
			$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("the specified path is invalid"))));

		$response->addAssign($this->zone, "innerHTML", utf8e($data));
	}
}
?>