<?

class sConsole extends Script
{
	var $running;

	function sConsole()
	{
		$running = "";
	}
	
	function Exec(&$system, &$response, $args)
	{
		if (!isset($this->scripts))
			$this->loadScripts();
	
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		if (!isset($args['cmdline']))
		{
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
					$response->addAssign("console_log", "innerHTML", "");
				}
				else if ($cmd == "list")
				{
					$response->addAssign("cmdline", "value", "");
					$response->addAppend("console_log", "innerHTML", utf8e("<div class=\"cmd\">] $cmd</div>"));
					$list = "";
					foreach ($this->scripts as $key => $script)
						$list .= "$key ";
						
					$response->addAppend("console_log", "innerHTML", utf8e("<div class=\"out\">".nl2br($list)."</div>"));
				}
				else if ($cmd == "zones")
				{
					$response->addAssign("cmdline", "value", "");
					$response->addAppend("console_log", "innerHTML", utf8e("<div class=\"cmd\">] $cmd</div>"));
					$list = "";
					for ($n = 0; $n < count($system->scripts); $n++)
					{
						$list .= "ZONE=".$system->scripts[$n]->zone." CLASS=".get_class($system->scripts[$n])."\n";
					}
						
					$response->addAppend("console_log", "innerHTML", utf8e("<div class=\"out\">".nl2br($list)."</div>"));
				}
				else
				{
					if ($this->execCommand($cmd, $stdout, $stderr, $response, $system))
						$response->addAssign("cmdline", "value", "");
					
					
					
					if (!empty($stderr))
						$response->addAppend("console_log", "innerHTML", utf8e("<div class=\"err\">".nl2br($stderr)."</div>"));
						
					if (!empty($stdout))
						$response->addAppend("console_log", "innerHTML", utf8e("<div class=\"out\">".nl2br($stdout)."</div>"));
				}
				
				$response->addScript("var console_log = document.getElementById(\"console_log\");console_log.scrollTop = console_log.scrollHeight;");
			}
		}
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
		
		$response->addAppend("console_log", "innerHTML", utf8e("<div class=\"cmd\">] $cmd</div>"));
		
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