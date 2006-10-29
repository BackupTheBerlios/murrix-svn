<?

class Script
{
	var $zone;
	var $active;
	var $actionHandlers;
	
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
	
	function addActionHandler($action)
	{
		if (!is_array($this->actionHandlers))
			$this->actionHandlers = array();
			
		$actionHandler = "action".ucf($action);
		
		if (method_exists($this, $actionHandler))
		{
			$this->actionHandlers[] = $action;
			return true;
		}
		
		return false;
	}

	function execute(&$system, $args)
	{
		if (!is_array($this->actionHandlers))
			$this->actionHandlers = array();
		
		if (in_array($args['action'], $this->actionHandlers))
		{
			$actionHandler = "action".ucf($args['action']);
			
			$this->$actionHandler($system, $args);
			return;
		}
		
		$this->draw($system, $args);
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