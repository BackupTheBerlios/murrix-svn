<?

class sSearch extends Script
{
	function sSearch()
	{
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
			$class = (isset($args['class_name']) ? $args['class_name'] : "");
			$class_query = (!empty($class) ? " AND property:class_name='$class'" : "");
			$children = fetch("FETCH node WHERE property:name LIKE '%$query_string%' $class_query NODESORTBY !property:version SORTBY property:class,property:name");
		}

		ob_start();
		
		include(gettpl("scripts/search"));

		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>