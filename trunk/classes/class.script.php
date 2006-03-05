<?

class Script
{
	var $zone;
	
	function Script()
	{
	}

	function EventHandler(&$system, &$response, $event, $args)
	{
	}

	function Exec(&$system, &$response, $args)
	{
	}

	function Draw(&$system, &$response, $args)
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
			if (empty($_SESSION['murrix']['path']))
			{
				global $site_config;
				$_SESSION['murrix']['path'] = $site_config['sites'][$_SESSION['murrix']['site']]['start'];
			}
			$args['node_id'] = getNode($_SESSION['murrix']['path']);
		}

		return $args['node_id'];
	}
	
	function PrintJavascript()
	{
	}
}

?>