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
			for ($n = 0; $n < count($this->scripts); $n++)
				$this->scripts[$n]->PrintJavascript();
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
			$class_name = "s".ucfirst($folder);
			$script = new $class_name();
			$this->RegisterScript($folder, $script);
		}
	}
	
	function RegisterScript($name, $script)
	{
		for ($n = 0; $n < count($this->scripts); $n++)
		{
			if ("s$name" == get_class($this->scripts[$n]))
				return;
		}
	
		if (is_array($this->scripts))
			array_push($this->scripts, $script);
		else
			$this->scripts = array($script);
	}

	function TriggerEvent($event, $arguments = null)
	{
		$response = new xajaxResponse();
		$this->TriggerEventIntern($response, $event, utf8d($arguments));
		return $response->getXML();
	}
	
	function TriggerEventIntern(&$response, $event, $arguments = null)
	{
		for ($n = 0; $n < count($this->scripts); $n++)
		{
			if (!empty($this->scripts[$n]->zone))
				$this->scripts[$n]->EventHandler($this, $response, $event, $arguments);
		}

		$response->addScript("endScript('$event');");
	}

	function Exec($name, $zone, $arguments = null)
	{
		if (empty($arguments) || $arguments == null || !isset($arguments))
                        $arguments = array();
	
		$response = new xajaxResponse();
		$this->ExecIntern($response, $name, $zone, utf8d($arguments));
		
		if (!empty($_SESSION['debug']))
			$response->addAlert($_SESSION['debug']);

		return $response->getXML();
	}

	function ExecIntern(&$response, $name, $zone, $arguments = null)
	{
		if (empty($arguments) || $arguments == null)
                        $arguments = array();
	
		$found = false;

		for ($n = 0; $n < count($this->scripts); $n++)
		{
			if ("s$name" == get_class($this->scripts[$n]))
			{
				$this->SetZone($name, $zone);
				$this->scripts[$n]->Exec($this, $response, $arguments);
				$found = true;
			}
			else if ($this->scripts[$n]->zone == $zone)
				$this->scripts[$n]->zone = "";
		}

		if (!$found)
			$response->addAlert("Exec: Error: No such script; $name");

		$response->addScript("endScript('$zone');");
	}

	function SetZone($name, $zone)
	{
		for ($n = 0; $n < count($this->scripts); $n++)
		{
			if ("s$name" == get_class($_SESSION['murrix']['System']->scripts[$n]))
				$this->scripts[$n]->zone = $zone;
			else if ($this->scripts[$n]->zone == $zone)
				$this->scripts[$n]->zone = "";
		}
	}
}

function ExecScript($name, $zone, $arguments)
{
	return $_SESSION['murrix']['System']->Exec($name, $zone, $arguments);
}

function TriggerEvent($event, $arguments)
{
	return $_SESSION['murrix']['System']->TriggerEvent($event, $arguments);
}
?>