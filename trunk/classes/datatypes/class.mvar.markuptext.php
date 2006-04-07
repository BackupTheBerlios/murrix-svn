<?

class mVarMarkuptext extends mVar
{
	function processText($text)
	{
		$_SESSION['murrix']['tmp']['processText'] = array();
		
		$text = preg_replace_callback("/\=\=\=?\=?\=?\=?(.+?)\=\=\=?\=?\=?\=?\n?/", array($this, "processSectionCallback"), $text);
		$text = preg_replace_callback("/\*\*(.+?)\*\*/", array($this, "processBoldCallback"), $text);
		$text = preg_replace_callback("/##(.+?)##/", array($this, "processItalicCallback"), $text);
		$text = preg_replace_callback("/__(.+?)__/", array($this, "processUnderlineCallback"), $text);
		$text = preg_replace_callback("/\-\-(.+?)\-\-/", array($this, "processStrikethroughCallback"), $text);
		$text = preg_replace_callback("/''(.+?)''/", array($this, "processMonospaceCallback"), $text);
		$text = preg_replace_callback("#(\s)(([a-zA-Z]+://|www\.)(.+))#", array($this, "processFreeLinkCallback"), $text);
		$text = preg_replace_callback("#\[(.+?)( +\[(.+)\])?\]#", array($this, "processLinkCallback"), $text);

		for ($n = 0; $n < count($_SESSION['murrix']['tmp']['processText']); $n++)
			$text .= "<div class=\"clear\"></div></div>";

		unset($_SESSION['murrix']['tmp']['processText']);
		return str_replace(array("\r\n", "\n", "\r"), "<br/>", $text);
	}

	function processSectionCallback($matches)
	{
		for ($num = 2; $num < strlen($matches[0]); $num++)
		{
			if ($matches[0]{$num} != '=')
				break;
		}
		$num--;

		$string = "";
		if (!empty($_SESSION['murrix']['tmp']['processText']))
		{
			while ($section = array_pop($_SESSION['murrix']['tmp']['processText']))
			{
				if ($section < $num)
				{
					array_push($_SESSION['murrix']['tmp']['processText'], $section);
					break;
				}

				$string .= "<div class=\"clear\"></div></div>";
			}
		}
		
		array_push($_SESSION['murrix']['tmp']['processText'], $num);
		return "$string<div class=\"clear\"></div><h$num>".$matches[1]."</h$num><div class=\"h{$num}_section\">";
	}

	function processFreeLinkCallback($matches)
	{
		$url = $matches[2];
		
		if (substr($url, 0, 4) == "www.")
			$url = "http://".$url;
		
		return $matches[1]."<a href=\"$url\">".$matches[2]."</a>";
	}

	function processBoldCallback($matches)
	{
		return "<span style=\"font-weight:bold;\">".$matches[1]."</span>";
	}

	function processItalicCallback($matches)
	{
		return "<span style=\"font-style:italic;\">".$matches[1]."</span>";
	}

	function processUnderlineCallback($matches)
	{
		return "<span style=\"text-decoration:underline;\">".$matches[1]."</span>";
	}

	function processStrikethroughCallback($matches)
	{
		return "<del>".$matches[1]."</del>";
	}

	function processMonospaceCallback($matches)
	{
		return "<code>".$matches[1]."</code>";
	}

	/* Link syntax
	[target_url=...		image_url=...	[Name of link/image]]
	 target_node=...	image_node=...
	*/
	function processLinkCallback($matches)
	{
		$args = explode(" ", $matches[1]);

		if (isset($matches[3]))
			$name = $matches[3];
		else
			$name = "";

		$target = "";
		$target_param = "";
		$image = "";
		$image_param = "";
		$style = "";
		$clear = "";
		$size = "real";
		foreach ($args as $arg)
		{
			$parts = explode("=", $arg);
			if (isset($parts[1]))
				$param = $parts[1];
			else
				$param = "";

			switch ($parts[0])
			{
			case "target_url":
				$target = "url";
				$target_param = $param;
				break;

			case "target_obj":
				$target = "obj";

				if (is_numeric($param))
					$target_param = $param;
				else
					$target_param = getNode($param);
				break;

			case "image_url":
				$image = "url";
				$image_param = $param;
				break;

			case "image_obj":
				$image = "obj";

				if (is_numeric($param))
					$image_param = $param;
				else
					$image_param = getNode($param);
				break;

			case "size":
				$size = $param;
				break;

			case "float":
				$style .= "float: $param;";
				break;

			case "margin":
				$style .= "margin: $param;";
				break;

			case "clear":
				$clear = "<div style=\"clear: $param;\"></div>";
				break;
			}
		}

		$text = $name;
		switch ($image)
		{
		case "url":
			if (!empty($name))
				$name = " alt=\"$name\" title=\"$name\"";
			list($width, $height, $type, $attr) = getimagesize($image_param);
			
			$text = "<img style=\"$style; width: {$width}px; height: {$height}px;\" src=\"$image_param\"$name/>$clear";
			break;

		case "obj":
			$object = new mObject($image_param);

			if ($object->getClassName() == "file")
			{
				if (empty($name))
					$name = $object->getVarValue("description");

				$thumb_id = $object->getVarValue("thumbnail_id");
				$filename = $object->getVarValue("file");
				$pathinfo = pathinfo($filename);

				$showtumb = false;

				if (!empty($thumb_id))
				{
					$thumbnail = new mThumbnail($thumb_id);

					if ($thumbnail->getRebuild())
					{
						$angle = $object->getMeta("angle");

						if (empty($angle))
							$angle = GetFileAngle($filename);

						if ($angle < 0) $angle = 360+$angle;
						else if ($angle > 360) $angle = 360-$angle;

						$maxsize = 150;
						if ($thumbnail->CreateFromFile($filename, $pathinfo['extension'], $maxsize, $maxsize, $angle))
						{
							if (!$thumbnail->Save())
								echo "Failed to create thumbnail<br/>";
							else
								$showtumb = true;
						}
					}
					else
						$showtumb = true;
				}

				if ($showtumb)
					$text = $thumbnail->Show(true, $name, $style).$clear;
				else
					$text = img(geticon(getfiletype($pathinfo['extension']), 128), $name, $style).$clear;
			}
			else
				$text = img(geticon("broken", 128), $name, $style).$clear;
			break;
		}

		switch ($target)
		{
		case "url":
			if (empty($text))
				$text = htmlentities($target_param);
			$text = "<a href=\"$target_param\">$text</a>";
			break;

		case "obj":
			$object = new mObject($target_param);

			if (empty($text))
				$text = $object->getName();

			if ($object->hasRight("read"))
				$text = cmd($text, "Exec('show','zone_main',Hash('path','".$object->getPath()."'))");
			break;
		}

		return $text;
	}
	
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;

		return $this->processText($value);
	}
	
	function getEdit($formname, $var_prefix = "")
	{
		return compiletpl("datatypes/markuptext", $this->getStandardArgs($formname, $var_prefix));
	}
}

?>