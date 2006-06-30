<?

class sXML extends Script
{
	var $imported_classes;
	var $imported_nodes;
	var $imported_links;

	function sXML()
	{
		$this->zone = "zone_main";
		$this->imported_classes = array();
		$this->imported_nodes = array();
		$this->imported_links = array();
	}
	
	function EventHandler(&$system, &$response, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "login":
			case "logout":
			if ($this->active)
				$this->Draw($system, $response, $args);
			break;
		}
	}
	
	function SortByVersion($a, $b)
	{
		return strnatcasecmp($a['version'], $b['version']);
	}
	
	function Exec(&$system, &$response, $args)
	{
		global $abspath, $wwwpath, $db_prefix;

		if ($args['action'] == "import" && isAdmin())
		{
			if (empty($args['node_id']))
			{
				$response->addAlert(ucf(i18n("you must specifiy a node")));
				return;
			}
			
			if (empty($args['file']))
			{
				$response->addAlert(ucf(i18n("you must upload a file to import")));
				return;
			}
			
			list($filename, $full_filename) = explode(":", $args['file']);
			
			ob_start();
			
			$xml = new mXml();
			
			echo ucf(i18n("log"))."<br/><hr/>";
			
			$data = "";
			
			$extension = pathinfo($filename, PATHINFO_EXTENSION);
			if ($extension == "bz2")
			{
				echo "Found bz2-compressed file.</br>";
				$bz = bzopen($full_filename, "r");
				while (!feof($bz))
					$data .= bzread($bz, 4096);
				bzclose($bz);
			}
			else
			{
				echo "Found uncompressed file.</br>";
				$bz = fopen($full_filename, "r");
				while (!feof($bz))
					$data .= fread($bz, 4096);
				fclose($bz);
			}
			
			$import_data = $xml->parseBackupXML($data);
			
			
			
			if (isset($import_data['verion']))
				echo "Version: ".$import_data['verion']."<br/>";
			
			if (isset($import_data['name']))
				echo "Created: ".$import_data['created']."<br/>";
			
			if (isset($import_data['name']))
				echo "Name: ".$import_data['name']."<br/>";
				
			if (isset($import_data['description']))
				echo "Description: ".$import_data['description']."<br/>";
			
			$this->imported_classes = array();
			$this->imported_nodes = array();
			$this->imported_links = array();
			
			//PrintPre($import_data['container']);
			
			foreach ($import_data['container'] as $container)
			{
				$xmldata = $container['xmldata'];
				unset($container['xmldata']);
				//PrintPre($container);
				//echo "<hr/>";
				switch ($xmldata)
				{
					case "node":
					$this->imported_nodes[] = $container;
					break;
					
					case "class":
					$this->imported_classes[] = $container;
					break;
					
					case "link":
					$this->imported_links[] = $container;
					break;
				}
			}
			
			$class_table = new mTable("classes");
			$vars_table = new mTable("vars");
			
			foreach ($this->imported_classes as $class)
			{
				echo "Checking database for existing class ".$class['name']."<br/>";
				
				$existing_class = $class_table->get("`name`='".$class['name']."'");
				
				if (count($existing_class) == 0)
				{
					echo "<span style=\"color:red\">No existing class named ".$class['name']." found</span><br/>";
					continue;
				}
				else
					echo "<span style=\"color:green\">Class named ".$class['name']." found, checking variables</span><br/>";
				
				$existing_vars = $vars_table->get("`class_name`='".$class['name']."'");
				
				foreach ($class['vars'] as $varname => $properties)
				{
					$found = false;
					foreach ($existing_vars as $existing_var)
					{
						if ($existing_var['name'] == $varname)
						{
							$found = true;
							break;
						}
					}
					
					if ($found)
						echo "<span style=\"color:green\">Found matching var named $varname, type ".$properties['type']."</span><br/>";
					else
						echo "<span style=\"color:red\">No matching var named $varname, type ".$properties['type']." found</span><br/>";
				}
			}
			
			$nodes_to_link = array();
			$id_conversion = array();
			$added_node_ids = array();
			$linked_node_ids = array();
			
			$node_table = new mTable("nodes");
			
			foreach ($this->imported_nodes as $container)
			{
				$node_id = $node_table->insert(array("created" => $container['created']));
				
				$node = new mObject();
				$node->node_id = $node_id;
				
				$id_conversion[$container['id']] = $node_id;
				$added_node_ids[] = $node_id;
				
				if (is_array($container['metadata']))
				{
					foreach ($container['metadata']['container'] as $metadata)
					{
						$node->setMeta($metadata['name'], $metadata['value']);
						echo "Created metadata name=".$metadata['name'].", value=".$metadata['value'].", node_id=".$node_id."<br/>";
					}
				}
				
				foreach ($container['objects'] as $objcontainer)
				{
					if (isset($objcontainer['xmldata']) && $objcontainer['xmldata'] == "object")
						$objects = array($objcontainer);
					else
						$objects = $objcontainer;
						
					usort(&$objects, array($this, "SortByVersion"));
					
					foreach ($objects as $object_array)
					{
						$object = new mObject();
						$object->setClassName($object_array['class_name']);
						$object->loadVars();
						
						$object->node_id = $node_id;
						$object->created = $object_array['created'];
						$object->version = $object_array['version'];
						$object->class_name = $object_array['class_name'];
						$object->name = $object_array['name'];
						$object->icon = $object_array['icon'];
						$object->language = $object_array['language'];
						
						if (isset($object_array['right']))
							$object->rights = $object_array['rights'];
						else
							$object->rights = "";
						
						if (isset($object_array['user']))
						{
							$user = new mUser();
							$user->setByUsername($object_array['user']);
							$object->user_id = $user->id;
						}
						else
							$object->user_id = $_SESSION['murrix']['user']->id;
						
						foreach ($object_array['vars'] as $key => $value)
						{
							if ($object->checkVarExistance($key) == 0)
							{
								echo "<span style=\"color:red\">Could not resolve - $key, skipping<br/></span>";
								continue;
							}
								
							$value['value'] = html_entity_decode($value['value']);
							if ($value['type'] == "file")
							{
								$extension = strtolower(pathinfo($value['value'], PATHINFO_EXTENSION));
								$oldfile = $args['filepath']."/".$value['file_id'].".$extension";
								
								if (!file_exists($oldfile))
									echo "<span style=\"color:red\">Could not find file - $oldfile, skipping<br/></span>";
								else
									$object->setVarValue($key, $value['value'].":$oldfile");
							}
							else if ($value['type'] == "thumbnail")
							{
								if (!empty($value['thumb_id']))
								{
									$oldfile = $args['thumbpath']."/".$value['thumb_id'].".jpg";
									
									if (!file_exists($oldfile))
										echo "<span style=\"color:red\">Could not find thumbfile - $oldfile, skipping<br/></span>";
									else
										$object->setVarValue($key, $value['thumb_id'].".jpg:$oldfile");
								}
							}
							else
								$object->setVarValue($key, $value['value']);
						}
							
						$object->save(true);
						echo "Created object name=".$object->name.", node_id=$node_id, id=".$object->id.",version=".$object->version.",language=".$object->language."<br/>";
					}
				}
			}
			
			$link_table = new mTable("links");
			
			foreach ($this->imported_links as $container)
			{
				$link_array = array();
				$link_array['node_top'] = $id_conversion[$container['node_top']];
				$link_array['node_bottom'] = $id_conversion[$container['node_bottom']];
				$link_array['type'] = $container['type'];
				
				$link_table->insert($link_array);
				echo "Linked node_top=".$link_array['node_top']." to node_bottom=".$link_array['node_bottom'].", type=".$link_array['type']."<br/>";
				
				if ($link_array['type'] == "sub")
					$linked_node_ids[] = $id_conversion[$container['node_bottom']];
			}
			
			$added_node_ids = array_unique($added_node_ids);
			$linked_node_ids = array_unique($linked_node_ids);
			
			$node_ids_to_link = array_diff($added_node_ids, $linked_node_ids);
			
			foreach ($node_ids_to_link as $node_id)
			{
				$link_array = array();
				$link_array['node_top'] = $args['node_id'];
				$link_array['node_bottom'] = $node_id;
				$link_array['type'] = "sub";
				
				$link_table->insert($link_array);
				echo "Linked node_top=".$link_array['node_top']." to node_bottom=".$link_array['node_bottom'].", type=".$link_array['type']."<br/>";
			}
			
			//print_r($import_data);
			$response->addAssign("zone_import_log", "innerHTML", utf8e("<br/>".ob_get_end()));
			return;
		}
		
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		ob_start();
		if (isAdmin())
		{
			include(gettpl("scripts/xml/view"));
		}
		else
		{
			$titel = ucf(i18n("error"));
			$text = ucf(i18n("not enough rights"));
			include(gettpl("message"));
		}
		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>