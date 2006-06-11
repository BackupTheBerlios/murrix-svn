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
		if (isset($args['cmdline']))
		{
			$cmd = trim($args['cmdline']);
			
			if (!empty($cmd))
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
					$list = "";
					foreach ($this->scripts as $key => $script)
						$list .= "$key\n";
						
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
		else
		{
			ob_start();
			include(gettpl("scripts/console/console"));
			$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
		}
	}
	
	function execCommand($cmd, &$stdout, &$stderr, &$response, &$system)
	{
		if (!empty($this->running) && isset($this->scripts[$this->running]))
		{
			if ($this->scripts[$this->running]->exec($cmd, $stdout, $stderr, $response, $system))
				$this->running = "";
				
			return true;
		}
		
		list($cmd2, $stdin) = explode(" ", $cmd, 2);
		
		$response->addAppend("console_log", "innerHTML", utf8e("<div class=\"cmd\">] $cmd</div>"));
		
		if (!isset($this->scripts[$cmd2]))
		{
			$stdout = "Unknown command";
			return false;
		}
		
		if (!$this->scripts[$cmd2]->exec($stdin, $stdout, $stderr, $response, $system))
			$this->running = $cmd2;
		else
			$this->running = "";
		
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