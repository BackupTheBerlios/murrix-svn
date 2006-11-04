<?

class mXML
{
	function mXML()
	{
	}
	
	function outputBackupXML($args)
	{
		global $version, $db_prefix;
		
		// An array of serializer options
		$serializer_options = array(	"addDecl" => true,
						"encoding" => "UTF8",
						"indent" => "  ",
						"rootName" => "murrix_xml",
						"defaultTagName" => "container");
		
		// Instantiate the serializer with the options
		$Serializer = &new XML_Serializer($serializer_options);
		
		$list = array();
		$list['version'] = $version;
		$list['created'] = date("Y-m-d H:i:s");
		
		$node_id = $_SESSION['murrix']['root_id'];
		if (isset($args['node_id']))
			$node_id = $args['node_id'];
		
		if (isset($args['name']))
			$list['name'] = $args['name'];
		else
			$list['name'] = "Unnamed MURRiX XML";
		
		if (isset($args['description']))
			$list['description'] = $args['description'];
		
		// fetch classes
		$vars_table = new mTable("vars");
		$node_table = new mTable("nodes");
		$links_table = new mTable("links");
		
		$class_list = array();
		$node_list = array();
		$link_list = array();
		
		$used_classes = array();
		$used_node_ids = array();
		
		$node_ids = getNodeIdTree($node_id);
		foreach ($node_ids as $node_id)
		{
			$used_node_ids[] = $node_id;
		
			$node = array();
			$node['xmldata'] = "node";
			$node['id'] = $node_id;
			$latest_obj = new mObject($node_id);
				
			$node['metadata'] = array();
			$metadata = $latest_obj->getAllMeta();
			foreach ($metadata as $meta)
				$node['metadata'][$meta['name']] = $meta['value'];
			
			$node['objects'] = array();
			
			$versions = fetch("FETCH object WHERE property:node_id='$node_id' NODESORTBY property:version,property:name");
			
			foreach ($versions as $version)
			{
				$object = array();
				$object['xmldata'] =  "object";
				
				$user = $version->getUser();
				$object['user'] = $user->username;
				
				$object['language'] =  $version->getLanguage();
				$object['rights'] =  $version->getRights();
				$object['icon'] =  $version->icon;
				$object['name'] =  $version->getName();
				$object['class_name'] =  $version->getClassName();
				$object['version'] =  $version->getVersion();
				$object['created'] =  $version->getCreated();
				
				foreach ($version->vars as $key => $var)
				{
					$object['variables'][$key]['type'] = $var->getType();
					$object['variables'][$key]['value'] = htmlentities($var->getValue(true));
				
					switch ($object['variables'][$key]['type'])
					{
						case "file":
						$object['variables'][$key]['fileid'] = $var->getId();
						break;
						
						case "thumbnail":
						$thumb = new mThumbnail($var->getValue(true));
						$object['variables'][$key]['fileid'] = $thumb->getId();
						break;
					}
				}
			
				$node['objects'][] = $object;
				
				if (!in_array($version->getClassName(), $used_classes))
				{
					$class = array();
					$class['xmldata'] = "class";
					$class['name'] = $version->getClassName();
					$class['icon'] = $version->getIcon(true);
					
					$vars = $vars_table->get("`class_name`='".$version->getClassName()."'");
					foreach ($vars as $var)
					{
						$class['variables'][$var['name']]['priority'] = $var['priority'];
						$class['variables'][$var['name']]['type'] = $var['type'];
						$class['variables'][$var['name']]['required'] = $var['required'];
						$class['variables'][$var['name']]['extra'] = $var['extra'];
						$class['variables'][$var['name']]['comment'] = $var['comment'];
					}
					
					$class_list[] = $class;
					$used_classes[] = $version->getClassName();
				}
			}
			
			$node_list[] = $node;
		}
		
		$links = $links_table->get();
		foreach ($links as $link)
		{
			if (in_array($link['node_top'], $used_node_ids) && in_array($link['node_bottom'], $used_node_ids))
			{
				$link2 = array();
				$link2['xmldata'] = "link";
				$link2['type'] = $link['type'];
				$link2['node_top'] = $link['node_top'];
				$link2['node_bottom'] = $link['node_bottom'];
				$link_list[] = $link2;
			}
		}

		$list = array_merge($list, $class_list, $node_list, $link_list);
		
		// Serialize the data structure
		$status = $Serializer->serialize($list);
		
		// Check whether serialization worked
		if (PEAR::isError($status))
			die($status->getMessage());
		
		// Display the XML document
		header("Content-type: application/force-download");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=\"".date("ymd-Hi")."-murrix-export.xml.bz2\"");
		header("Content-type: application/x-bzip2");
		echo bzcompress($Serializer->getSerializedData(), 9);
	}
	
	function parseXML($args)
	{
		// Instantiate the serializer
		$Unserializer = &new XML_Unserializer();
		
		// Serialize the data structure
		$status = $Unserializer->unserialize($args['data']);
		
		// Check whether serialization worked
		if (PEAR::isError($status))
			die($status->getMessage());
		
		// Display the PHP data structure
		$import_data = $Unserializer->getUnserializedData();
		
		$parent = new mObject($args['node_id']);
		
		$logtext = "";
	
		if (isset($import_data['version']))
			$logtext .= "Version: ".$import_data['version']."<br/>";
		
		if (isset($import_data['name']))
			$logtext .= "Created: ".$import_data['created']."<br/>";
		
		if (isset($import_data['name']))
			$logtext .= "Name: ".$import_data['name']."<br/>";
			
		if (isset($import_data['description']))
			$logtext .= "Description: ".$import_data['description']."<br/>";
		
		$imported_classes = array();
		$imported_nodes = array();
		$imported_links = array();
		$imported_users = array();
		$imported_groups = array();
		
		foreach ($import_data['container'] as $container)
		{
			switch ($container['xmldata'])
			{
				case "node":
				$imported_nodes[] = $container;
				break;
				
				case "class":
				$imported_classes[] = $container;
				break;
				
				case "link":
				$imported_links[] = $container;
				break;
				
				case "user":
				$imported_users[] = $container;
				break;
				
				case "group":
				$imported_groups[] = $container;
				break;
			}
		}
		
		$logtext .= "Found ".count($imported_classes)." classes<br/>";
		$logtext .= "Found ".count($imported_nodes)." nodes<br/>";
		$logtext .= "Found ".count($imported_links)." links<br/>";
		$logtext .= "Found ".count($imported_users)." users<br/>";
		$logtext .= "Found ".count($imported_groups)." groups<br/>";
		
		$class_table = new mTable("classes");
		$vars_table = new mTable("vars");
		
		foreach ($imported_classes as $class)
		{
			$logtext .= "Checking database for existing class ".$class['name']."<br/>";
			
			$existing_class = $class_table->get("`name`='".$class['name']."'");
			
			if (count($existing_class) == 0)
			{
				$logtext .= "No existing class named ".$class['name']." found, creating<br/>";
				
				$class_table->insert(array("name"=>$class['name'], "default_icon"=>$class['icon']));
				
				foreach ($class['variables'] as $varname => $properties)
					$vars_table->insert(array("class_name"=>$class['name'], "name"=>$varname, "priority"=>$properties['priority'], "required"=>$properties['required'], "type"=>$properties['type'], "extra"=>$properties['extra'], "comment"=>$properties['comment']));
				
				continue;
			}
			else
				$logtext .= "Class named ".$class['name']." found, checking variables<br/>";
			
			$class_object = new mObject();
			$class_object->setClassName($class['name']);
			$class_object->loadVars();
			
			foreach ($class['variables'] as $varname => $properties)
			{
				if ($class_object->checkVarExistance($varname) == 0)
				{
					$logtext .= "Could not resolve - $varname, skipping, this data will be ignored!<br/>";
					continue;
				}
			}
		}
		
		$nodes_to_link = array();
		$id_conversion = array();
		$added_node_ids = array();
		$linked_node_ids = array();
		
		$node_table = new mTable("nodes");
		
		foreach ($imported_nodes as $container)
		{
			if (!isset($container['created']))
				$container['created'] = date("Y-m-d H:i:s");
						
			$node_id = $node_table->insert(array("created" => $container['created']));
			
			$node = new mObject();
			$node->node_id = $node_id;
			
			$id_conversion[$container['id']] = $node_id;
			$added_node_ids[] = $node_id;
			
			if (is_array($container['metadata']))
			{
				foreach ($container['metadata'] as $key => $value)
				{
					$node->setMeta($key, $value);
					$logtext .= "Created metadata name=".$key.", value=".$value.", node_id=".$node_id."<br/>";
				}
			}
			
			foreach ($container['objects'] as $object_array)
			{
				$object = new mObject();
				$object->setClassName($object_array['class_name']);
				$object->loadVars();
				
				if (!isset($object_array['created']))
					$object_array['created'] = date("Y-m-d H:i:s");
				
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
					$object->rights = $parent->getRights();
				
				if (isset($object_array['user']))
				{
					$user = new mUser();
					$user->setByUsername($object_array['user']);
					$object->user_id = $user->id;
				}
				else
					$object->user_id = $_SESSION['murrix']['user']->id;
				
				if (is_array($object_array['variables']))
				{
					foreach ($object_array['variables'] as $key => $value)
					{
						if ($object->checkVarExistance($key) == 0)
						{
							$logtext .= "Could not resolve - $key, skipping<br/>";
							continue;
						}
						
						if (!is_array($value['value']))
							$value['value'] = html_entity_decode($value['value']);
							
						if ($value['type'] == "file")
						{
							$extension = strtolower(pathinfo($value['value'], PATHINFO_EXTENSION));
							$oldfile = $args['filepath']."/".$value['fileid'].".$extension";
							
							if (!file_exists($oldfile))
								$logtext .= "Could not find file - $oldfile, skipping<br/>";
							else
								$object->setVarValue($key, $value['value'].":$oldfile");
						}
						else if ($value['type'] == "thumbnail")
						{
							if (!empty($value['thumb_id']))
							{
								$oldfile = $args['thumbpath']."/".$value['fileid'].".jpg";
								
								if (!file_exists($oldfile))
									$logtext .= "Could not find thumbfile - $oldfile, skipping<br/>";
								else
									$object->setVarValue($key, $value['fileid'].".jpg:$oldfile");
							}
						}
						else
							$object->setVarValue($key, $value['value']);
					}
				}
				
				$object->save(true);
				guessObjectType($object);
				$logtext .= "Created object name=".$object->name.", node_id=$node_id, id=".$object->id.",version=".$object->version.",language=".$object->language."<br/>";
			}
		}
		
		$link_table = new mTable("links");
		
		foreach ($imported_links as $container)
		{
			$link_array = array();
			$link_array['node_top'] = $id_conversion[$container['node_top']];
			$link_array['node_bottom'] = $id_conversion[$container['node_bottom']];
			$link_array['type'] = $container['type'];
			
			$link_table->insert($link_array);
			$logtext .= "Linked node_top=".$link_array['node_top']." to node_bottom=".$link_array['node_bottom'].", type=".$link_array['type']."<br/>";
			
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
			$logtext .= "Linked node_top=".$link_array['node_top']." to node_bottom=".$link_array['node_bottom'].", type=".$link_array['type']."<br/>";
		}
		
		return mMsg::add("mXml::parseXML", $logtext, false);
	}
	
	
	function getFeeds()
	{
		$table = new mTable("rssexports");
		return $table->get();
	}
	
	function outputFeed($id)
	{
		$table = new mTable("rssexports");
		$feeds = $table->get("`id`='$id'");
		
		if (count($feeds) == 0)
		{
			echo "No such feed found";
			return;
		}
		
		$feed = $feeds[0];
		
		// An array of serializer options
		$serializer_options = array(	"addDecl" => true,
						"encoding" => "ISO-8859-1",
						"indent" => "  ",
						"rootName" => "rss",
						"rootAttributes"  => array("version" => "2"),
						"defaultTagName" => "item");
		
		// Instantiate the serializer with the options
		$Serializer = &new XML_Serializer($serializer_options);
		
		$children = fetch($feed['fetch']);
		
		$list = array();
		
		$list['channel']['title'] = $feed['title'];
		$list['channel']['link'] = "http://".$_SERVER["HTTP_HOST"];
		$list['channel']['description'] = $feed['description'];
		$list['channel']['language'] = "sv";
		$list['channel']['webMaster'] = $feed['admin'];
		
		foreach ($children as $child)
		{
			$object = array();
		
			$object['guid'] = $list['channel']['link']."/rssbackend.php?node_id=".$child->getNodeId();
			$object['title'] = $child->getName();
			$object['link'] = $list['channel']['link']."/rssbackend.php?node_id=".$child->getNodeId();
			$object['pubDate'] = date("r", strtotime($child->getCreated()));
			$author = $child->getUser();
			$object['author'] = "email@email.com (".$author->name.")";
			
			$text = $child->getVarValue("text");
			if (empty($text))
				$text = $child->getVarValue("description");
			
			$object['description'] = strip_tags($text);
		
			$list['channel'][] = $object;
		}
		
		// Serialize the data structure
		$status = $Serializer->serialize($list);
		
		// Check whether serialization worked
		if (PEAR::isError($status))
			die($status->getMessage());
		
		// Display the XML document
		header("Content-type: application/xml");
		echo $Serializer->getSerializedData();
	}
}
?>