<?

class Script
{
	var $zone;
	var $active;
	
	function Script()
	{
	}

	function onActive($arguments)
	{
		$this->active = true;
	}

	function eventHandler(&$system, &$response, $event, $args)
	{
	}

	function execute(&$system, &$response, $args)
	{
	}

	function draw(&$system, &$response, $args)
	{

	}

	function getNodeId(&$args)
	{
		if (isset($args['node_id']))
			return $args['node_id'];
		else if (isset($args['path']))
			$args['node_id'] = getNode($args['path']);
		else
		{
			//if (empty($_SESSION['murrix']['path']) || $_SESSION['murrix']['path'] = "/")
			//	$_SESSION['murrix']['path'] = $_SESSION['murrix']['default_path'];
			
			$args['node_id'] = getNode($_SESSION['murrix']['path']);
		}

		return $args['node_id'];
	}
	
	function printJavascript()
	{
	}
}

?>