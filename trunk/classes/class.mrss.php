<?

class mRSS
{
	function mRSS()
	{
	}
	
	function getFeeds()
	{
		global $db_prefix;

		$query = "SELECT * FROM `".$db_prefix."rssexports`";

		$result = mysql_query($query) or die("getList: " . mysql_errno() . " " . mysql_error());
		
		$list = array();
		
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$list[] = $row;
			
		return $list;
	}
	
	function outputFeed($id)
	{
		global $db_prefix;
		
		$query = "SELECT * FROM `".$db_prefix."rssexports` WHERE `id`='$id'";

		$result = mysql_query($query) or die("outputFeed: " . mysql_errno() . " " . mysql_error());
		
		$feed = mysql_fetch_array($result, MYSQL_ASSOC);
	
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