<?

class mVarPassword extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		return parent::getValue($raw);
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		return "<input class=\"form\" id=\"$this->id\" name=\"$this->id\" type=\"password\" value=\"$this->value\">&nbsp;<input class=\"form\" id =\"".$this->id."b\" name=\"".$this->id."b\" type=\"password\" value=\"$this->value\">";
	}
}

class mVarIcon extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		return parent::getValue($raw);
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		return parent::getEdit($formname);
	}
}

class mVarDate extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		return parent::getValue($raw);
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		return "<input class=\"form\" id=\"v$this->id\" name=\"v$this->id\" type=\"text\" value=\"$this->value\"/> <a name=\"button$this->id\" id=\"button$this->id\" href=\"javascript:void(null);\" onclick=\"var calendar=new CalendarPopup('popupCalendarDiv');calendar.select(document.getElementById('$formname').v$this->id,'button$this->id','yyyy-MM-dd');\">".img(imgpath("calendar.jpg"), ucf(i18n("calendar")))."</a>";
	}
}

class mVarThumbnailid extends mVar
{
	function mVarThumbnailId()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		return parent::getValue($raw);
	}
	
	function Save()
	{
		$thumbnail = new mThumbnail();
		$thumbnail->Save();
		$this->value = $thumbnail->id;
	
		return parent::Save();
	}
	
	function Remove()
	{
		$value = $this->getValue(true);
		if (!empty($value))
		{
			$thumbnail = new mThumbnail($value);
			$thumbnail->Remove();
		}
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		return "<input class=\"form\" id=\"$this->id\" name=\"$this->id\" type=\"hidden\" class=\"hidden\" value=\"$this->value\">";
	}
}

class mVarHidden extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		return parent::getValue($raw);
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		$parts = explode(":", $this->extra);
				
		switch ($parts[0])
		{
		case "user":
			$value = $_SESSION['murrix']['user']->$parts[1]; // ? IS THIS CORRECT??!?!?!
			break;
		case "date":
			$value = date("Y-m-d");
			break;
		case "datetime":
			$value = date("Y-m-d H:i:s");
			break;
		default:
			$value = $this->getValue(true);
			break;
		}
		return "<input class=\"form\" id=\"$this->id\" name=\"$this->id\" type=\"hidden\" class=\"hidden\" value=\"$value\">";
	}
}

class mVarNode extends mVar
{
	function mVarNode()
	{
		$this->mVar();
	}

	function getValue($raw = false)
	{
		return parent::getValue($raw);
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		return "<input class=\"form\" id=\"v$this->id\" name=\"v$this->id\" type=\"text\" value=\"$this->value\"/> <a href=\"javascript:void(null);\" onclick=\"popWin = open('browse.php?input_id=v$this->id&form_id=$formname','PopUpWindow','width=300,height=300,scrollbars=1,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false\">".ucf(i18n("browse"))."</a>";
	}
}

class mVarFile extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;

		if (empty($value))
			return "";
			
		global $abspath;

		$parts = SplitFilepath($value);
		return "$abspath/files/".$this->value_id.".".$parts['extension'];
	}
	
	function Save()
	{
		$data = $this->getValue(true);
		
		if (empty($data))
			return true;

		global $abspath;

		//$data = "filename.txt:/tmp/phpSffowB_tmpfile";

		$names = explode(":", $data);

		$parts = SplitFilepath($names[0]);

		$this->value = $names[0];
		
		if (parent::Save())
		{
			$filename = "$abspath/files/".$this->value_id.".".$parts['extension'];
			if (!copy($names[1], $filename))
			{
				return "Error while moving uploaded file from ".$names[1]." to $filename";
				//return false;
			}
		}
	
		return true;
	}
	
	function Remove()
	{
		@unlink($this->getValue());
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		return "<input class=\"form\" disabled id=\"nv$this->id\" name=\"nv$this->id\" type=\"text\" value=\"$this->value\"/> <a href=\"javascript:void(null);\" onclick=\"popWin = open('single_upload.php?varid=v$this->id','PopUpWindow','width=250,height=80,scrollbars=0,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false\">".ucf(i18n("upload file"))."</a><input class=\"hidden\" id=\"v$this->id\" name=\"v$this->id\" value=\"".$this->value.":".$this->getValue()."\" type=\"hidden\"/>";

		//<input disabled class=\"form\" id=\"v$this->id\" name=\"v$this->id\" type=\"text\" value=\"".$this->value."\"/>

	}
}

class mVarThumbnail extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		return parent::getValue($raw);
	}
	
	function Save()
	{
		$data = $this->getValue(true);
		
		if (empty($data))
			return true;

		if (!strpos($data, ":"))
		{
			$thumbnail = new mThumbnail($data);
			$thumbnail->duplicate();
		}
		else
		{
			$names = explode(":", $data);
	
			$parts = SplitFilepath($names[0]);
	
			$thumbnail = new mThumbnail();
			
			$filename = $names[1];
			
			$angle = GetFileAngle($filename);
			
			$maxsizex = (empty($this->extra) ? 150 : $this->extra);
			
			$thumbnail->CreateFromFile($filename, $parts['ext'], $maxsizex, $maxsizex, $angle);
		}
		
		if (!$thumbnail->Save())
			return false;
		
		$this->value = $thumbnail->id;
		
		return parent::Save();
	}
	
	function Remove()
	{
		$value = parent::getValue(true);
		
		$thumbnail = new mThumbnail($value);
		
		$thumbnail->Remove();
	
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		return "<input class=\"form\" disabled id=\"nv$this->id\" name=\"nv$this->id\" type=\"text\"/> <a href=\"javascript:void(null);\" onclick=\"popWin = open('single_upload.php?varid=v$this->id','PopUpWindow','width=250,height=80,scrollbars=0,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false\">".ucf(i18n("upload thumbnail"))."</a><input class=\"hidden\" id=\"v$this->id\" name=\"v$this->id\" type=\"hidden\" value=\"$this->value\"/>";
	}
}

class mVarSelection extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;
	
		$selections = explode(",", $this->extra);
		foreach ($selections as $selection)
		{
			$parts = explode("=", $selection);
			if ($parts[0] == $value)
				return $parts[1];
		}
		return "NULL";
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		$select = "<select class=\"form\" id=\"$this->id\" name=\"$this->id\">";
		$selections = explode(",", $this->extra);
		foreach ($selections as $selection)
		{
			$parts = explode("=", $selection);
			$select .= "<option value=\"".$parts[0]."\" ".($parts[0] == $this->value ? "selected" : "").">".$parts[1]."</option>";
		}
		$select .= "</select>";
		return $select;
	}
}

class mVarArray extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;
		
		return array_diff(explode("\n", $value), array(""));
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		return "<textarea class=\"form\" id=\"$this->id\" name=\"$this->id\">$this->value</textarea>";
	}
}

class mVarBoolean extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;
			
		return (intval($value) ? "true" : "false");
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		return ucf(i18n("true"))." <input class=\"form\" type=\"radio\" id=\"$this->id\" name=\"$this->id\" value=\"1\"".(intval($this->value) ? " checked" : "").">&nbsp;".ucf(i18n("false"))." <input class=\"form\" type=\"radio\" id=\"$this->id\" name=\"$this->id\" value=\"0\" value=\"0\"".(!intval($this->value) ? " checked" : "").">";
	}
}

class mVarTextline extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		return parent::getValue($raw);
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		return "<input class=\"form\" id=\"$this->id\" name=\"$this->id\" type=\"text\" value=\"$this->value\">";
	}
}

class mVarText extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		$value = parent::getValue($raw);
		if ($raw)
			return $value;
			
		return nl2br($value);
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		//if (empty($this->extra))
			return "<textarea style=\"width: 100%; height: 200px;\" class=\"form\" id=\"$this->id\" name=\"$this->id\">$this->value</textarea>";
		/*else
		{
			$parts = explode("x", $this->extra);
			return "<textarea class=\"form\" id=\"$this->id\" name=\"$this->id\" cols=\"".$parts[0]."\" rows=\"".$parts[1]."\">$this->value</textarea>";
		}*/
	}
}

class mVarMarkuptext extends mVar
{
	function mVarMarkuptext()
	{
		$this->mVar();
	}

	function processText($text)
	{
		$text = preg_replace_callback("/\*\*(.+?)\*\*/", array($this, "processBoldCallback"), $text);
		$text = preg_replace_callback("/\/\/(.+?)\/\//", array($this, "processItalicCallback"), $text);
		$text = preg_replace_callback("/__(.+?)__/", array($this, "processUnderlineCallback"), $text);
		$text = preg_replace_callback("/\-\-(.+?)\-\-/", array($this, "processStrikethroughCallback"), $text);
		$text = preg_replace_callback("/''(.+?)''/", array($this, "processMonospaceCallback"), $text);
		$text = preg_replace_callback("#(\s)(([a-zA-Z]+://|www\.)(.+))#", array($this, "processFreeLinkCallback"), $text);
		$text = preg_replace_callback("/\[(.+?)( +\[(.+)\])?\]/", array($this, "processLinkCallback"), $text);
		return nl2br($text);
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
		foreach ($args as $arg)
		{
			$parts = explode("=", $arg);
			$param = $parts[1];

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
				

			case "float":
				$style .= "float: $param;";
				break;

			case "margin":
				$style = "margin: $param;";
				break;
			}
		}

		$text = $name;
		switch ($image)
		{
		case "url":
			if (!empty($name))
				$name = " alt=\"$name\" title=\"$name\"";

			$text = "<img style=\"$style\" src=\"$image_param\"$name/>";
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
					$text = $thumbnail->Show(true, $name, $style);
				else
					$text = img(geticon(getfiletype($pathinfo['extension']), 128), $name, $style);
			}
			else
				$text = img(geticon("broken", 128), $name, $style);
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
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		//if (empty($this->extra))
			$text = "<textarea style=\"width: 100%; height: 200px;\" class=\"form\" id=\"v$this->id\" name=\"v$this->id\">$this->value</textarea>";
		/*else
		{
			$parts = explode("x", $this->extra);
			$text = "<textarea class=\"form\" disabled id=\"v$this->id\" name=\"v$this->id\" cols=\"".$parts[0]."\" rows=\"".$parts[1]."\">$this->value</textarea>";
		}*/
		return $text;
		return "$text <a href=\"javascript:void(null);\" onclick=\"popWin = open('richtext.php?varid=v$this->id&formname=$formname','PopUpWindow','width=605,height=400,scrollbars=0,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false\">Open Editor</a>";
	}
}

class mVarXhtml extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function getValue($raw = false)
	{
		return parent::getValue($raw);
	}
	
	function Save()
	{
		return parent::Save();
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function getEdit($formname)
	{
		//if (empty($this->extra))
			$text = "<textarea style=\"width: 100%; height: 200px;\" class=\"form\" id=\"v$this->id\" name=\"v$this->id\">$this->value</textarea>";
		/*else
		{
			$parts = explode("x", $this->extra);
			$text = "<textarea class=\"form\" disabled id=\"v$this->id\" name=\"v$this->id\" cols=\"".$parts[0]."\" rows=\"".$parts[1]."\">$this->value</textarea>";
		}*/
		return $text;
		return "$text <a href=\"javascript:void(null);\" onclick=\"popWin = open('richtext.php?varid=v$this->id&formname=$formname','PopUpWindow','width=605,height=400,scrollbars=0,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false\">Open Editor</a>";
	}
}

class mVar
{
	var $id;
	var $class_name;
	var $name;
	var $type;
	var $extra;
	var $priority;
	var $object_id;
	var $value;
	var $value_id;
	
	function mVar()
	{
		$this->value = "";
		$this->value_id = 0;
	}
	
	function SetByArray($array)
	{
		$this->id = $array['id'];
		$this->class_name = $array['class_name'];
		$this->name = $array['name'];
		$this->type = $array['type'];
		$this->extra = $array['extra'];
		$this->priority = $array['priority'];
		$this->object_id = $array['object_id'];

		$this->value = isset($array['data']) ? $array['data'] : "";
		$this->value_id = isset($array['value_id']) ? $array['value_id'] : "";
	}
	
	function getName($raw = false)
	{
		if ($raw)
			return $this->name;
		
		return ucfirst(str_replace("_", " ", $this->name));
	}

	function getType()
	{
		return $this->type;
	}
	
	function getValue($raw = false)
	{
		if ($raw)
			return $this->value;
			
		$value = htmlspecialchars($this->value);
		
		return $value;
	}

	function setValue($value)
	{
		$this->value = $value;
	}
	
	function getEdit($formname)
	{
		return "<input class=\"form\" id=\"$this->id\" name=\"$this->id\" type=\"text\" value=\"$this->value\">";
	}
	
	function Save()
	{
		global $db_prefix;

		$data = $this->value;
		
		if ($data == "")
			return $this->Remove();

		$data = str_replace("'", "\'", $data);
	
		if ($this->value_id > 0)
		{
			$query = "UPDATE `".$db_prefix."values` SET data='$data', object_id='$this->object_id', var_id='$this->id' WHERE id = '$this->value_id'";
	
			$result = mysql_query($query);
			if (!$result)
			{
				$message = "<b>An error occured while updateing</b><br/>";
				$message .= "<b>Table:</b> values<br/>";
				$message .= "<b>Query:</b> $query<br/>";
				$message .= "<b>Error Num:</b> " . mysql_errno() . "<br/>";
				$message .= "<b>Error:</b> " . mysql_error() . "<br/>";
				return $message;
			}
		}
		else
		{
			$query = "INSERT INTO `".$db_prefix."values` (data, object_id, var_id) VALUES('$data', '$this->object_id', '$this->id')";
	
			$result = mysql_query($query);
			if (!$result)
			{
				$message = "<b>An error occured while inserting</b><br>";
				$message .= "<b>Table:</b> values<br>";
				$message .= "<b>Query:</b> $query<br>";
				$message .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
				$message .= "<b>Error:</b> " . mysql_error() . "<br>";
				return $message;
			}
			
			$this->value_id = mysql_insert_id();
		}
		
		return true;
	}
	
	function Remove()
	{
		global $db_prefix;
		
		$query = "DELETE FROM `".$db_prefix."values` WHERE object_id = '$this->object_id' AND var_id = '$this->id'";
		$result = mysql_query($query);
		if (!$result)
		{
			$message = "<b>An error occured while deleting</b><br>";
			$message .= "<b>Table:</b> values<br>";
			$message .= "<b>Query:</b> $query<br>";
			$message .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
			$message .= "<b>Error:</b> " . mysql_error() . "<br>";
			echo $message;
			return false;
		}
		return true;
	}
}
?>
