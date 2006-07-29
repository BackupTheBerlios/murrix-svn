<?

require_once("3dparty/xajax/xajax.inc.php");

class mSystem
{
	var $xajax;
	var $scripts;

	function mSystem($requestURI = "")
	{
		if (empty($requestURI))
		{
			global $wwwpath;
			$requestURI = "$wwwpath/index.php";
		}
		
		$this->xajax = new xajax($requestURI);
		$this->xajax->debugOff();
		$this->xajax->errorHandlerOn();
		$this->xajax->statusMessagesOn();
		
		$this->xajax->registerFunction("ExecScript");
		$this->xajax->registerFunction("TriggerEvent");
		
		$this->LoadScripts();
	}

	function PrintHeader()
	{
		$this->xajax->printJavascript("3dparty/xajax/");
		echo "<script type=\"text/javascript\" src=\"system/murrix.js\"></script>\n";

		?>
		<script type="text/javascript">
		<!--
			<?
			foreach ($this->scripts as $key => $value)
				$this->scripts[$key]->PrintJavascript();
			?>
		// -->
		</script>
		<?
	}
	
	function Process()
	{
		$this->xajax->processRequests();
	}
	
	function LoadScripts()
	{
		global $abspath;
		
		$folders = GetSubfolders("$abspath/scripts");
		foreach ($folders as $folder)
		{
			if (isset($this->scripts[$folder]))
				continue;
				
			$class_name = "s".ucfirst($folder);
			$this->scripts[$folder] = new $class_name();
		}
	}
	
	function TriggerEvent($event, $arguments = null)
	{
		if (empty($arguments) || $arguments == null || !isset($arguments))
                        $arguments = array();
	
		$response = new xajaxResponse();
		$this->TriggerEventIntern($response, $event, utf8d($arguments));
		$response->addScript("Behaviour.apply();");
		return $response->getXML();
	}
	
	function TriggerEventIntern(&$response, $event, $arguments = null)
	{
		foreach ($this->scripts as $key => $value)
		{
			//if ($this->scripts[$key]->active)
			$this->scripts[$key]->EventHandler($this, $response, $event, $arguments);
		}

		$response->addScript("endScript('$event');");
	}

	function Exec($name, $arguments = null)
	{
		if (empty($arguments) || $arguments == null || !isset($arguments))
                        $arguments = array();
	
		$response = new xajaxResponse();
		$this->ExecIntern($response, $name, utf8d($arguments));
		
		if (!empty($_SESSION['debug']))
			$response->addAlert($_SESSION['debug']);

		$response->addScript("Behaviour.apply();");
		return $response->getXML();
	}

	function ExecIntern(&$response, $name, $arguments = null)
	{
		if (empty($arguments) || $arguments == null)
                        $arguments = array();
	
		if (!isset($this->scripts[$name]))
			$response->addAlert("Exec: Error: No such script; $name");
		else
		{
			if (isset($arguments['zone']))
				$this->scripts[$name]->zone = $arguments['zone'];
		
			$this->scripts[$name]->active = true;
			$this->scripts[$name]->Exec($this, $response, $arguments);
			
			foreach ($this->scripts as $key => $value)
			{
				if ($key == $name)
					continue;
					
				if ($this->scripts[$key]->zone == $this->scripts[$name]->zone)
					$this->scripts[$key]->active = false;
			}
		}

		$response->addScript("endScript('$name');");
	}

	function SetZone($name, $zone)
	{
		if (isset($this->scripts[$name]))
			$this->scripts[$name]->zone = $zone;
	}
	
	function makeActive($name, $arguments = null)
	{
		if (empty($arguments) || $arguments == null)
                        $arguments = array();

		if (isset($this->scripts[$name]))
		{
			$this->scripts[$name]->active = true;
			$this->scripts[$name]->onActive($arguments);
		}
	}
}

function ExecScript($name, $arguments)
{
	return $_SESSION['murrix']['system']->Exec($name, $arguments);
}

function TriggerEvent($event, $arguments)
{
	return $_SESSION['murrix']['system']->TriggerEvent($event, $arguments);
}
?>