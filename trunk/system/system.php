<?

require_once("3dparty/xajax/xajax.inc.php");

class mSystem
{
	var $xajax;
	var $scripts;
	var $zones;
	var $js;
	
	var $ajax;
	var $firstrun;
	var $transport;
	var $command;

	function mSystem()
	{
		$this->firstrun = true;
		$this->xajax = new xajax();
		$this->xajax->debugOff();
		$this->xajax->errorHandlerOn();
		$this->xajax->statusMessagesOn();
		
		$this->xajax->registerFunction("ExecScript");
		$this->xajax->registerFunction("TriggerEvent");
	}

	function printHeader()
	{
		$this->xajax->printJavascript("3dparty/xajax/");

		?>
		<script type="text/javascript">
		<!--
			<?
			foreach ($this->scripts as $key => $value)
				$this->scripts[$key]->printJavascript();
			?>
			
			function getDefaultCommand()
			{
				return "exec=show&node_id=<?=getNode($_SESSION['murrix']['default_path'])?>";
			}
			
			function runZoneJS()
			{
				Behaviour.register(bhRules);
				<?=$this->getJSScript()?>
				Behaviour.apply();
			}
			
			var bhRules = {
			<?
				if ($_SESSION['murrix']['system']->transport == "ajax")
				{
				?>
					'a.cmd' : function(element) {
						var parts = element.href.split("?");
						
						if (typeof parts[1] != 'undefined')
							element.href = "javascript:setRun('"+parts[1]+"')";
					}
				<?
				}
			?>
			};
		// -->
		</script>
		<?
	}
	
	function execCommand($cmd)
	{
		$exec = "";
		$arguments = array();
		$cmd_string = "";
		
		foreach ($cmd as $key => $value)
		{
			if ($key == "exec")
				$exec = $value;
			else
				$arguments[$key] = $value;
				
			$cmd_string .= "$key=$value&";
		}
		
		$cmd_string = substr($cmd_string, 0, strlen($cmd_string)-1);
		
		if (empty($exec))
		{
			$arguments['node_id'] = getNode($_SESSION['murrix']['default_path']);
			$exec = "show";
		}
		
		$this->execIntern($cmd_string, $exec, $arguments);
	}
	
	function process()
	{
		if (count($this->zones) > 0)
		{
			foreach ($this->zones as $name => $value)
				$this->zones[$name]['changed'] = false;
		}
			
		$this->js = "";
	
		$this->ajax = true;
		$this->xajax->processRequests();
		$this->ajax = false;
		// exec scripts with data from POST or GET
		
		$this->command = empty($_GET) ? $_POST : $_GET;
		
		$this->execCommand($this->command);
	}
	
	function loadScripts()
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
	
	function loadScript($name)
	{
		if (isset($this->scripts[$name]))
			return;
			
		$class_name = "s".ucfirst($name);
		$this->scripts[$name] = new $class_name();
	}
	
	function triggerEvent($event, $arguments = null)
	{
		if (empty($arguments) || $arguments == null || !isset($arguments))
                        $arguments = array();
	
		$response = new xajaxResponse();
		$this->triggerEventIntern($event, utf8d($arguments));
		
		if (count($this->zones) > 0)
		{
			foreach ($this->zones as $name => $value)
			{
				if ($this->zones[$name]['changed'])
					$response->addAssign($name, "innerHTML", $this->zones[$name]['data']);
			}
		}
		
		$response->addScript($this->js);
		$response->addScript("Behaviour.apply();");
		$response->addScript("endScript('$event');");
		return $response->getXML();
	}
	
	function triggerEventIntern($event, $arguments = null)
	{
		foreach ($this->scripts as $key => $value)
		{
			//if ($this->scripts[$key]->active)
			$this->scripts[$key]->eventHandler($this, $event, $arguments);
		}
	}

	function execute($cmd, $name, $arguments = null)
	{
		if (empty($arguments) || $arguments == null || !isset($arguments))
                        $arguments = array();
	
		$response = new xajaxResponse();
		$this->execIntern($cmd, $name, utf8d($arguments));
		
		if (!empty($_SESSION['debug']))
			$response->addAlert($_SESSION['debug']);
		
		$_SESSION['murrix']['callcache'] = array();
		$_SESSION['murrix']['querycache'] = array();
		
		foreach ($this->zones as $name => $value)
		{
			if ($this->zones[$name]['changed'])
				$response->addAssign($name, "innerHTML", $this->zones[$name]['data']);
		}
		
		$response->addScript($this->js);
		$response->addScript("endScript('$cmd', '".$this->scripts[$name]->zone."');");
		$response->addScript("Behaviour.apply();");

		return $response->getXML();
	}

	function execIntern($cmd, $name, $arguments = null)
	{
		if (empty($name))
			return;
	
		if (empty($arguments) || $arguments == null)
                        $arguments = array();
	
		if (!isset($this->scripts[$name]))
			$this->addAlert("Exec: Error: No such script; $name");
		else
		{
			if (isset($arguments['zone']))
				$this->scripts[$name]->zone = $arguments['zone'];
		
			$this->scripts[$name]->active = true;
			
			foreach ($this->scripts as $key => $value)
			{
				if ($key == $name)
					continue;
					
				if ($this->scripts[$key]->zone == $this->scripts[$name]->zone)
					$this->scripts[$key]->active = false;
			}
			
			$this->scripts[$name]->execute($this, $arguments);
		}
	}

	function setZone($name, $zone)
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
	
	function createZone($name)
	{
		$data = "<div id=\"$name\">";
		
		if (isset($this->zones[$name]))
			$data .= utf8d($this->zones[$name]['data']);
			
		$data .= "</div>";
		
		return $data;
	}
	
	function addAlert($msg)
	{
		$this->js .= "alert($msg);\n";
	}
	
	function addJSScript($js)
	{
		$this->js .= "$js\n";
	}
	
	function setZoneData($name, $data)
	{
		$this->zones[$name]['data'] = $data;
		$this->zones[$name]['changed'] = true;
	}
	
	function addZoneData($name, $data)
	{
		$this->zones[$name]['data'] .= $data;
		$this->zones[$name]['changed'] = true;
	}
	
	function getZoneData($name)
	{
		if (isset($this->zones[$name]))
			return $this->zones[$name]['data'];
		
		return "";
	}
	
	function addRedirect($cmd)
	{
		if ($this->transport == "ajax")
			$this->js .= "setHash('$cmd');\n";
		else
			$this->js .= "setHref('$cmd');\n";
	}
	
	function getJSScript()
	{
		return $this->js;
	}
}

function ExecScript($cmd, $name, $arguments)
{
	return $_SESSION['murrix']['system']->execute($cmd, $name, $arguments);
}

function TriggerEvent($event, $arguments)
{
	return $_SESSION['murrix']['system']->triggerEvent($event, $arguments);
}
?>