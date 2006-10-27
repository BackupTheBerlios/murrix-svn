<?

class sConsole extends Script
{
	var $running;

	function sConsole()
	{
		$this->zone = "zone_main";
		$this->running = "";
		$this->logg = $this->getLogTitle();
	}
	
	function execute(&$system, $args)
	{
		if (!isset($this->scripts))
			$this->loadScripts();
	
		$this->draw($system, $args);
	}
	
	function getLogTitle()
	{
		$title = "Welcome to the MURRiX administration console<br/>";
		$title .= "============================================<br/>";
		return $title;
	}
	
	function setLogText(&$system, $text)
	{
		$text = $this->getLogTitle().$text;
		
		$system->setZoneData("console_log", utf8e($text));
		$this->logg = $text;
	}
	
	function addLogText(&$system, $text)
	{
		$system->addZoneData("console_log", utf8e($text));
		$this->logg .= $text;
	}
	
	function draw(&$system, $args)
	{
		if (!isset($args['cmdline']))
		{
			$system->setZoneData($this->zone, utf8e(compiletpl("scripts/console/view", array("logtext"=>$this->logg))));
			$system->addJSScript("document.getElementById('cmdline').focus();");
			$this->running = "";
		}
		
		if (isset($args['cmdline']) || isset($args['initcmd']))
		{
			$cmd = isset($args['cmdline']) ? $args['cmdline'] : $args['initcmd'];
		
			$cmd = trim($cmd);
			
			//if (!empty($cmd))
			{
				$output = "";
				
				if ($cmd == "clear")
				{
					$system->addJSScript("document.getElementById('cmdline').value='';");
					$this->setLogText($system, "");
				}
				else if ($cmd == "list")
				{
					$system->addJSScript("document.getElementById('cmdline').value='';");
					$this->addLogText($system, "<div class=\"cmd\">] $cmd</div>");
					
					$list = "";
					foreach ($this->scripts as $key => $script)
						$list .= "$key ";
						
					$this->addLogText($system, "<div class=\"out\">".nl2br($list)."</div>");
				}
				else if ($cmd == "zones")
				{
					$system->addJSScript("document.getElementById('cmdline').value='';");
					$this->addLogText($system, "<div class=\"cmd\">] $cmd</div>");
					
					$list = "";
					foreach ($system->scripts as $name => $script)
					{
						$list .= "ZONE=".$system->scripts[$name]->zone." CLASS=$name ACTIVE=".$system->scripts[$name]->active."\n";
					}
						
					$this->addLogText($system, "<div class=\"out\">".nl2br($list)."</div>");
				}
				else
				{
					if ($this->execCommand($cmd, $stdout, $stderr, $system))
						$system->addJSScript("document.getElementById('cmdline').value='';");
					
					
					
					if (!empty($stderr))
						$this->addLogText($system, "<div class=\"err\">".nl2br($stderr)."</div>");
						
					if (!empty($stdout))
						$this->addLogText($system, "<div class=\"out\">".nl2br($stdout)."</div>");
				}
			}
		}
		
		$system->addJSScript("var console_log = document.getElementById(\"console_log\");console_log.scrollTop = console_log.scrollHeight;");
	}
	
	function execCommand($cmd, &$stdout, &$stderr, &$system)
	{
		if (!empty($this->running) && isset($this->scripts[$this->running]))
		{
			if ($this->scripts[$this->running]->exec($cmd, $stdin, $stdout, $stderr, $system))
			{
				$this->running = "";
				$system->addJSScript("document.getElementById('cmdline').type='text';");
			}
				
			return true;
		}
		
		$system->addJSScript("document.getElementById('cmdline').type='text';");
		
		if (empty($cmd))
			return false;
			
		$cmds = explode("|", $cmd);
		
		$this->addLogText($system, "<div class=\"cmd\">] $cmd</div>");
		
		foreach ($cmds as $cmd)
		{
			$cmd = trim($cmd);
			list($cmd2, $args) = explode(" ", $cmd, 2);
			
			if (!isset($this->scripts[$cmd2]))
			{
				$stdout = ucf(i18n("unknown command"))." - $cmd";
				return false;
			}
			
			$stdout = "";
			$stderr = "";
			
			$this->scripts[$cmd2]->stage = 0;
			
			if (!$this->scripts[$cmd2]->exec($args, $stdin, $stdout, $stderr, $system))
				$this->running = $cmd2;
			else
				$this->running = "";
				
			$stdin = $stdout;
		}
		
		return true;
	}
	
	function loadScripts()
	{
		$this->scripts = array();
		
		global $abspath;
		$files = GetSubfiles("$abspath/cscripts");
		foreach ($files as $file)
		{
			$name = basename($file, ".php");
			$class_name = "cs".ucfirst($name);
			$this->scripts[$name] = new $class_name();
		}
	}
}

?>