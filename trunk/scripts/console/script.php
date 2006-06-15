<?

class sConsole extends Script
{
	var $running;

	function sConsole()
	{
		$this->zone = "zone_main";
		$this->running = "";
		$this->logg = "";
	}
	
	function Exec(&$system, &$response, $args)
	{
		if (!isset($this->scripts))
			$this->loadScripts();
	
		$this->Draw($system, $response, $args);
	}
	
	function setLogText(&$response, $text)
	{
		$response->addAssign("console_log", "innerHTML", utf8e($text));
		$this->logg = $text;
	}
	
	function addLogText(&$response, $text)
	{
		$response->addAppend("console_log", "innerHTML", utf8e($text));
		$this->logg .= $text;
	}
	
	function Draw(&$system, &$response, $args)
	{
		if (!isset($args['cmdline']))
		{
			$logtext = $this->logg;
			ob_start();
			include(gettpl("scripts/console/console"));
			$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
			$response->addScript("document.getElementById('cmdline').focus();");
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
					$response->addAssign("cmdline", "value", "");
					$this->setLogText($response, "");
				}
				else if ($cmd == "list")
				{
					$response->addAssign("cmdline", "value", "");
					$this->addLogText($response, "<div class=\"cmd\">] $cmd</div>");
					
					$list = "";
					foreach ($this->scripts as $key => $script)
						$list .= "$key ";
						
					$this->addLogText($response, "<div class=\"out\">".nl2br($list)."</div>");
				}
				else if ($cmd == "zones")
				{
					$response->addAssign("cmdline", "value", "");
					$this->addLogText($response, "<div class=\"cmd\">] $cmd</div>");
					
					$list = "";
					foreach ($system->scripts as $name => $script)
					{
						$list .= "ZONE=".$system->scripts[$name]->zone." CLASS=$name ACTIVE=".$system->scripts[$name]->active."\n";
					}
						
					$this->addLogText($response, "<div class=\"out\">".nl2br($list)."</div>");
				}
				else
				{
					if ($this->execCommand($cmd, $stdout, $stderr, $response, $system))
						$response->addAssign("cmdline", "value", "");
					
					
					
					if (!empty($stderr))
						$this->addLogText($response, "<div class=\"err\">".nl2br($stderr)."</div>");
						
					if (!empty($stdout))
						$this->addLogText($response, "<div class=\"out\">".nl2br($stdout)."</div>");
				}
			}
		}
		
		$response->addScript("var console_log = document.getElementById(\"console_log\");console_log.scrollTop = console_log.scrollHeight;");
	}
	
	function execCommand($cmd, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($this->running) && isset($this->scripts[$this->running]))
		{
			if ($this->scripts[$this->running]->exec($cmd, $stdin, $stdout, $stderr, $response, $system))
			{
				$this->running = "";
				$response->addAssign("cmdline","type","text");
			}
				
			return true;
		}
		
		$response->addAssign("cmdline","type","text");
		
		if (empty($cmd))
			return false;
			
		$cmds = explode("|", $cmd);
		
		$this->addLogText($response, "<div class=\"cmd\">] $cmd</div>");
		
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
			
			if (!$this->scripts[$cmd2]->exec($args, $stdin, $stdout, $stderr, $response, $system))
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