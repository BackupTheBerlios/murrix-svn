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
		return parent::getEdit($formname);
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
	
	function getEdit($formname)
	{
		return "<input class=\"form\" disabled id=\"v$this->id\" name=\"v$this->id\" type=\"text\" value=\"$this->value\"/> <a href=\"javascript:void(null);\" onclick=\"popWin = open('browse.php?input_id=v$this->id&form_id=sEdit','PopUpWindow','width=300,height=300,scrollbars=1,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false\">".ucf(i18n("browse"))."</a>";
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
