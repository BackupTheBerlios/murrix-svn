<?

class sSearch extends Script
{
	function sSearch()
	{
		$this->zone = "zone_main";
	}
	
	function Exec(&$system, &$response, $args)
	{
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		$query_string = "";
		$class = "";
		$children = array();
			
		if (is_array($args) && !empty($args['query']))
		{
			$query_string = trim($args['query']);
			$query_string2 = str_replace(" ", "%", $query_string);
			$class = (isset($args['class_name']) ? $args['class_name'] : "");
			$class_query = (!empty($class) ? " AND property:class_name='$class'" : "");
			$children = fetch("FETCH node WHERE property:name LIKE '%$query_string2%' $class_query NODESORTBY property:version SORTBY property:name");

			$children = getReadable($children);
		}

		$system->setZoneData($this->zone, utf8e(compiletpl("scripts/search", array("objects"=>$children, "class"=>$class, "query_string"=>$query_string))));
	}
}
?>