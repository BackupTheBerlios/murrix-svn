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

	function eventHandler(&$system, $event, $args)
	{
	}

	function execute(&$system, $args)
	{
	}

	function draw(&$system, $args)
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