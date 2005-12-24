<?

class sInstall extends Script
{
	function sInstall()
	{
	}
	
	function Exec(&$system, &$response, $args)
	{
		if (!isset($args['stage']))
			$args['stage'] = 1;
			
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		ob_start();
		include(gettpl("install/stage".$args['stage']));
		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>