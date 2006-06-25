<?

class mXML
{
	function mXML()
	{
	}
	
	function outputBackupXML($args)
	{
		global $version, $db_prefix, $version;
		
		// An array of serializer options
		$serializer_options = array(	"addDecl" => true,
						"encoding" => "UTF8",
						"indent" => "  ",
						"rootName" => "murrix_export",
						"defaultTagName" => "container");
		
		// Instantiate the serializer with the options
		$Serializer = &new XML_Serializer($serializer_options);
		
		$list = array();
		$list['version'] = $version;
		$list['created'] = date("Y-m-d H:i:s");
		
		if (isset($args['name']))
			$list['name'] = $args['name'];
		
		if (isset($args['description']))
			$list['description'] = $args['description'];
		
		// fetch classes
		$vars_table = new mTable("vars");
		$node_table = new mTable("nodes");
		$links_table = new mTable("links");
		
		$class_list = array();
		$node_list = array();
		$link_list = array();
		
		$added_classes = array();
		
		$nodes = $node_table->get();
		foreach ($nodes as $node)
		{
			$node['xmldata'] = "node";
			$latest_obj = new mObject($node['id']);
			
			if (isset($args['metadata']))
			{
				$node['metadata'] = array();
				$metadata = $latest_obj->getAllMeta();
				foreach ($metadata as $meta)
				{
					unset($meta['id']);
					unset($meta['node_id']);
					$node['metadata'][] = $meta;
				}
			}
			
			$node['objects'] = array();
			
			if (isset($args['allversions']))
				$objects = fetch("FETCH object WHERE property:node_id='".$node['id']."' NODESORTBY property:version,property:name");
			else
				$objects = array($latest_obj);
			
			foreach ($objects as $object)
			{
				$object->xmldata = "object";
				$object->loadVars();
				unset($object->error);
				unset($object->id);
				unset($object->class_icon);
				unset($object->node_id);
				
				$user = $object->getUser();
				$object->user = $user->username;
				unset($object->user_id);
				
				$group = $object->getGroup();
				$object->group = $group->name;
				unset($object->group_id);
				
				foreach ($object->vars as $key => $var)
				{
					unset($object->vars[$key]->object_id);
					//unset($object->vars[$key]->value_id);
					unset($object->vars[$key]->required);
					unset($object->vars[$key]->priority);
					unset($object->vars[$key]->id);
					unset($object->vars[$key]->comment);
					unset($object->vars[$key]->extra);
					unset($object->vars[$key]->class_name);
					unset($object->vars[$key]->name);
					
					if ($object->vars[$key]->type == "file")
						$object->vars[$key]->file_id = $object->vars[$key]->value_id;
					else if ($object->vars[$key]->type == "thumbnail")
					{
						$thumb = new mThumbnail($object->vars[$key]->value);
						$object->vars[$key]->thumb_id = $thumb->id;
					}
					
					unset($object->vars[$key]->value_id);
					
					$object->vars[$key]->value = htmlentities($object->vars[$key]->value);
				}
			
				$node['objects'][$object->version] = $object;
				
				if (!in_array($object->class_name, $added_classes))
				{
					$class = array();
					$class['xmldata'] = "class";
					$class['name'] = $object->class_name;
					$object->loadClassIcon();
					$class['icon'] = $object->class_icon;
					$class['vars'] = array();
					
					$vars = $vars_table->get("`class_name`='".$object->class_name."'");
					foreach ($vars as $var)
					{
						$name = $var['name'];
						unset($var['id']);
						unset($var['name']);
						unset($var['class_name']);
						
						$class['vars'][$name] = $var;
					}
					
					$class_list[] = $class;
					$added_classes[] = $object->class_name;
				}
			}
			
			$node_list[] = $node;
		}
		
		if (isset($args['links']))
		{
			$links = $links_table->get();
			foreach ($links as $link)
			{
				unset($link['id']);
				$link['xmldata'] = "link";
				$link_list[] = $link;
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
	
	function parseBackupXML($data)
	{
		// Instantiate the serializer
		$Unserializer = &new XML_Unserializer();
		
		// Serialize the data structure
		$status = $Unserializer->unserialize($data);
		
		// Check whether serialization worked
		if (PEAR::isError($status))
		{
			echo $status->getMessage();
			return;
		}
		
		// Display the PHP data structure
		return $Unserializer->getUnserializedData();
	}
	
	
	function outputClassXML($id = 0)
	{
		global $version;
		
		// An array of serializer options
		$serializer_options = array(	"addDecl" => true,
						"encoding" => "ISO-8859-1",
						"indent" => "  ",
						"rootName" => "classes",
						"rootAttributes"  => array("version" => $version),
						"defaultTagName" => "container");
		
		// Instantiate the serializer with the options
		$Serializer = &new XML_Serializer($serializer_options);
		
		$classes = getClassList(true);

		$list = array();
		
		$list['title'] = "MURRiX XML-export";
		
		foreach ($classes as $class)
		{
			$object = new mObject();
			$object->class_name = $class['name'];
			$object->loadVars();
			
			$vars = $object->getVars();
			
			foreach ($vars as $var)
			{
				$variable = array();
				$variable['name'] = $var->name;
				$variable['type'] = $var->type;
				$variable['extra'] = $var->extra;
				$variable['comment'] = $var->comment;
				$variable['required'] = $var->required;
				$variable['priority'] = $var->priority;
				
				$class[] = $variable;
			}
		
			$list[] = $class;
		}
		
		/*echo "<pre>";
		print_r($list);
		echo "</pre>";*/
		
		// Serialize the data structure
		$status = $Serializer->serialize($list);
		
		// Check whether serialization worked
		if (PEAR::isError($status))
			die($status->getMessage());
		
		// Display the XML document
		header("Content-type: application/xml");
		echo $Serializer->getSerializedData();
		//$this->parseClassXML($Serializer->getSerializedData());
	}
	
	function parseClassXML($data)
	{
		// Instantiate the serializer
		$Unserializer = &new XML_Unserializer();
		
		// Serialize the data structure
		$status = $Unserializer->unserialize($data);
		
		// Check whether serialization worked
		if (PEAR::isError($status))
			die($status->getMessage());
		
		// Display the PHP data structure
		echo "<pre>";
		print_r($Unserializer->getUnserializedData());
		echo "<pre>";
	}
	
	// This function is not working...
	function outputFeed($id)
	{
		global $version;
		// An array of serializer options
		$serializer_options = array(	"addDecl" => true,
						"encoding" => "ISO-8859-1",
						"indent" => "  ",
						"rootName" => "murrix",
						"rootAttributes"  => array("version" => $version),
						"defaultTagName" => "object");
		
		// Instantiate the serializer with the options
		$Serializer = &new XML_Serializer($serializer_options);
		
		$children = fetch($feed['fetch']);
		
		$list = array();
		
		$list['title'] = "MURRiX XML-export";
		
		
		foreach ($children as $child)
		{
			$object = array();
		
			$vars = $child->getVars();
		
			$object['guid'] = $list['channel']['link']."/rssbackend.php?node_id=".$child->getNodeId();
			$object['title'] = $child->getName();
			$object['link'] = $list['channel']['link']."/rssbackend.php?node_id=".$child->getNodeId();
			$object['pubDate'] = date("r", strtotime($child->getCreated()));
			$author = new mObject($child->getCreator());
			$object['author'] = "email@email.com (".$author->getName().")";
			
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