<?

class sImport extends Script
{
	function sImport()
	{
		$this->zone = "zone_main";
		
		$this->addActionHandler("clearlog");
		$this->addActionHandler("import_upload");
		$this->addActionHandler("import_custom");
		$this->addActionHandler("import_custom2");
		$this->addActionHandler("import_xml");
	}
	
	function eventHandler(&$system, $event, $args)
	{
		switch ($event)
		{
			case "newlang":
			case "newlocation":
			case "login":
			case "logout":
			if ($this->active)
				$this->draw($system, $args);
			break;
		}
	}
	
	function actionClearlog(&$system, $args)
	{
		$system->setZoneData("zone_import_log", "");
	}
	
	function actionImport_upload(&$system, $args)
	{
		global $abspath;
		$chroot = "$abspath/upload";
		
		$object = new mObject($this->getNodeId($args));
		
		if ($object->getNodeId() <= 0)
		{
			$system->addAlert(ucf(i18n("invalid upload target")));
			return;
		}
		
		if (!$object->hasRight("write"))
		{
			$system->addAlert(ucf(i18n("not enough rights")));
			return;
		}
		
		// Validate path... only folders under upload
		
		if (count($args['filenames']) == 0)
		{
			$system->addAlert(ucf(i18n("you must select some files or folders to import")));
			return;
		}
		
		$path = empty($args['path']) ? "/" : urldecode($args['path']);
		$fullpath = extractPath("$chroot$path");
		
		$testpath = substr($fullpath, 0, strlen($chroot));
		if ($testpath != $chroot)
		{
			$system->addAlert(ucf(i18n("this path is not allowed")));
			return;
		}
		
		$logtext = "<strong>Beginning import from upload folder</strong> - ".date("Y-m-d H:i:s")."<hr/>";
		
		foreach ($args['filenames'] as $filename)
		{
			if (is_dir("$fullpath$filename"))
			{
				$logtext .= importDir($object, "$fullpath$filename");
			}
			else if (is_file("$fullpath$filename"))
			{
				createObject($object, $filename, "file", array("file" => "$filename:$fullpath$filename"));
				$logtext .= "Created ".$object->getPathInTree()."/$filename<br/>";
			}
			else
				$logtext .= "Did not find $path$filename - skipping<br/>";
		}
		
		$logtext .= "<hr/><strong>Finished import</strong> - ".date("Y-m-d H:i:s")."<br/><br/>";
		
		$system->paddZoneData("zone_import_log", utf8e($logtext));
	}
	
	function actionImport_custom(&$system, $args)
	{
		$object = new mObject($this->getNodeId($args));

		if ($object->getNodeId() == 0 || !$object->hasRight("write"))
		{
			$system->addAlert(ucf(i18n("you do not have enough rights")));
			return;
		}
	
		if (empty($args['file']))
		{
			$system->addAlert(ucf(i18n("you must upload a file to import")));
			return;
		}
		
		list($filename, $full_filename) = explode(":", $args['file']);
		$filedata = getFileData($filename, $full_filename);
		
		$lines = explode("\n", $filedata);
		
		$args['line'] = $lines[0];
		$args['number'] = substr_count($lines[0], $args['delimiter']);
		
		$data = compiletplWithOutput("scripts/import/custom2", $args, $object);
		$javascript = $args['output']['js'];
		
		$system->setAjaxZoneData("zone_import_custom", utf8e($data));
		
		if (!empty($javascript))
			$system->addJSScript($javascript);
	}
	
	function actionImport_custom2(&$system, $args)
	{
		$object = new mObject($this->getNodeId($args));

		if ($object->getNodeId() == 0 || !$object->hasRight("write"))
		{
			$system->addAlert(ucf(i18n("you do not have enough rights")));
			return;
		}
	
		if (empty($args['file']))
		{
			$system->addAlert(ucf(i18n("you must upload a file to import")));
			return;
		}
		
		list($filename, $full_filename) = explode(":", $args['file']);
		$filedata = getFileData($filename, $full_filename);
		
		$lines = explode("\n", $filedata);
		
		$fields = array_flip($args['fields']);
		
		$count = importLines($lines, $fields, $args['class_name'], $object);
		
		if (mMsg::isError($count))
		{
			$system->addAlert(mMsg::getText($count));
			return;
		}
		
		$logtext = "<strong>Beginning import from custom file</strong> - ".date("Y-m-d H:i:s")."<hr/>";
		
		$logtext .= ucf(i18n("converted"))." $count ".i18n("lines to objects - class")." ".str_replace("_", " ", $args['class_name'])."<br/>";
		
		$logtext .= "<hr/><strong>Finished import</strong> - ".date("Y-m-d H:i:s")."<br/><br/>";
		
		$system->paddZoneData("zone_import_log", utf8e($logtext));
		
		$data = compiletplWithOutput("scripts/import/custom", $args, $object);
		$javascript = $args['output']['js'];
		
		$system->setAjaxZoneData("zone_import_custom", utf8e($data));
		
		if (!empty($javascript))
			$system->addJSScript($javascript);
	}
	
	function actionImport_xml(&$system, $args)
	{
		$object = new mObject($this->getNodeId($args));

		if ($object->getNodeId() == 0 || !$object->hasRight("write"))
		{
			$system->addAlert(ucf(i18n("you do not have enough rights")));
			return;
		}
	
		if (empty($args['file']))
		{
			$system->addAlert(ucf(i18n("you must upload a file to import")));
			return;
		}
		
		list($filename, $full_filename) = explode(":", $args['file']);
		$filedata = getFileData($filename, $full_filename);
		
		
		$logtext = "<strong>Beginning import from xml file</strong> - ".date("Y-m-d H:i:s")."<hr/>";
		
		$xml = new mXml();
		
		$msgid = $xml->parseXML(array("node_id" => $object->getNodeId(), "data" => $filedata));
		
		$logtext .= mMsg::getText($msgid);
		
		$logtext .= "<hr/><strong>Finished import</strong> - ".date("Y-m-d H:i:s")."<br/><br/>";
		
		$system->paddZoneData("zone_import_log", utf8e($logtext));
	}

	function draw(&$system, $args)
	{
		if (empty($args['view']))
			$args['view'] = "xml";
	
		$object = new mObject($this->getNodeId($args));
		
		$javascript = "";
		$data = "";
		if ($object->getNodeId() > 0)
		{
			if ($object->hasRight("create"))
			{
				$data = compiletplWithOutput("scripts/import/view", $args, $object);
				$javascript = $args['output']['js'];
			}
			else
				$data = compiletpl("message", array("title"=>ucf(i18n("error")), "message"=>ucf(i18n("not enough rights"))));
		}
		
		$system->setZoneData($this->zone, utf8e($data));
		
		if (!empty($javascript))
			$system->addJSScript($javascript);
	}
}
?>