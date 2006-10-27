<?

class sPoll extends Script
{
	function sPoll()
	{
		$this->zone = "zone_poll";
	}
	
	function EventHandler(&$system, &$response, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "login":
			case "logout":
			if ($this->active)
				$this->Draw($system, $response, $args);
			break;
		}
	}
	
	function Exec(&$system, &$response, $args)
	{
		if (isset($args['node_id']))
		{
			if (isAnonymous())
			{
				$system->addAlert(ucf(i18n("anonymous user can not vote")));
				return;
			}
			
			if (!isset($args['answer']))
			{
				$system->addAlert(ucf(i18n("you must choose an alternative")));
				return;
			}
		
			$poll = new mObject($args['node_id']);
			
			if ($poll->getNodeId() > 0)
			{
				$now = time();
				
				if (strtotime($poll->getVarValue("closedate")) < $now)
				{
					$system->addAlert(ucf(i18n("this poll is closed")));
					return;
				}
				
				if (strtotime($poll->getVarValue("opendate")) > $now)
				{
					$system->addAlert(ucf(i18n("this poll is not open yet")));
					return;
				}
				
				$answers = fetch("FETCH node WHERE link:node_top='".$poll->getNodeId()."' AND link:type='sub' AND property:class_name='poll_answer' AND property:name='".$_SESSION['murrix']['user']->id."' NODESORTBY property:version");
				
				if (count($answers) > 0)
				{
					$system->addAlert(ucf(i18n("you have already voted in this poll")));
					return;
				}
			
				$answer = new mObject();
				$answer->setClassName("poll_answer");
				$answer->loadVars();
				
				$answer->name = $_SESSION['murrix']['user']->id;
				$answer->language = $_SESSION['murrix']['language'];
				$answer->rights = $poll->rights;;
				
				$answer->setVarValue("answer", $args['answer']);

				$answer->save();
		
				clearNodeFileCache($poll->getNodeId());
				$answer->linkWithNode($poll->getNodeId());
			}
		}
	
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		$system->setZoneData($this->zone, utf8e(compiletpl("scripts/poll/view", array())));
	}
}
?>