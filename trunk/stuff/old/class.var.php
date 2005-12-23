<?

class mVarPassword extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function GetValue($raw = false)
	{
		return parent::GetValue($raw);
	}
	
	function Save($data)
	{
		return parent::Save($data);
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		return "<input id =\"$this->id\" name=\"$this->id\" type=\"password\" value=\"\">&nbsp;<input id =\"".$this->id."b\" name=\"".$this->id."b\" type=\"password\" value=\"\">";
	}
}

class mVarIcon extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function GetValue($raw = false)
	{
		return parent::GetValue($raw);
	}
	
	function Save($data)
	{
		return parent::Save($data);
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		return parent::GetEdit($formname);
	}
}

class mVarDate extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function GetValue($raw = false)
	{
		return parent::GetValue($raw);
	}
	
	function Save($data)
	{
		return parent::Save($data);
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		return parent::GetEdit($formname);
	}
}

class mVarHidden extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function GetValue($raw = false)
	{
		return parent::GetValue($raw);
	}
	
	function Save($data)
	{
		return parent::Save($data);
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function GetEdit($formname)
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
		}
		return "<input id =\"$this->id\" name=\"$this->id\" type=\"hidden\" class=\"hidden\" value=\"$value\">";
	}
}

class mVarFile extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function GetValue($raw = false)
	{
		$value = parent::GetValue($raw);
		if ($raw)
			return $value;

		global $abspath;
			
		$data = basename($value);
		$parts = SplitFilepath($data);
		return "$abspath/files/".$this->value_id.".".$parts['ext'];
	}
	
	function Save($data)
	{
		if (empty($data))
			return true;

		global $abspath;

		//$data = "filename.txt:/tmp/phpSffowB_tmpfile";

		$names = explode(":", $data);

		$parts = SplitFilepath($names[0]);
		
		if (parent::Save($names[0]))
		{
			$filename = "$abspath/files/".$this->value_id.".".$parts['ext'];
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
		@unlink($this->GetValue());
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		return "<input disabled id=\"nv$this->id\" name=\"nv$this->id\" type=\"text\"/> <a href=\"javascript:void(null);\" onclick=\"popWin = open('upload.php?varid=v$this->id','PopUpWindow','width=210,height=80,scrollbars=0,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false\">Upload File</a><input class=\"hidden\" id=\"v$this->id\" name=\"v$this->id\" type=\"hidden\"/>";
//<input id =\"$this->id\" name=\"$this->id\" type=\"file\"/>";
	}
}

class mVarThumbnail extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function GetValue($raw = false)
	{
		return parent::GetValue($raw);
	}
	
	function Save($data)
	{
		global $abspath;

		if (empty($_FILES[$this->id]['name']))
			return true;
		
		$value = parent::GetValue($raw);
		
		$thumbnail = new mThumbnail($value);
		
		$filename = $_FILES[$this->id]['tmp_name'];
		
		$angle = GetFileAngle($filename);
		
		$maxsizex = (empty($this->extra) ? 150 : $this->extra);
		
		$thumbnail->CreateFromFile($filename, $maxsizex, $maxsizey, $angle);
		
		if (!$thumbnail->Save())
			return false;
		
		$data = $thumbnail->id;
		
		return parent::Save($data);
	}
	
	function Remove()
	{
		$value = parent::GetValue($raw);
		
		$thumbnail = new mThumbnail($value);
		
		$thumbnail->Remove();
	
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		return "<input id =\"$this->id\" name=\"$this->id\" type=\"file\"/>";
	}
}

class mVarSelection extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function GetValue($raw = false)
	{
		$value = parent::GetValue($raw);
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
	
	function Save($data)
	{
		return parent::Save($data);
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		$select = "<select id =\"$this->id\" name=\"$this->id\">";
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
	
	function GetValue($raw = false)
	{
		$value = parent::GetValue($raw);
		if ($raw)
			return $value;
		
		return array_diff(explode("\n", $value), array(""));
	}
	
	function Save($data)
	{
		return parent::Save($data);
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		return "<textarea id =\"$this->id\" name=\"$this->id\">$this->value</textarea>";
	}
}

class mVarBoolean extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function GetValue($raw = false)
	{
		$value = parent::GetValue($raw);
		if ($raw)
			return $value;
			
		return (intval($value) ? "True" : "False");
	}
	
	function Save($data)
	{
		return parent::Save($data);
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		return "True <input type=\"radio\" id =\"$this->id\" name=\"$this->id\" value=\"1\"".(intval($this->value) ? " checked" : "").">&nbsp;False <input type=\"radio\" id =\"$this->id\" name=\"$this->id\" value=\"0\" value=\"0\"".(!intval($this->value) ? " checked" : "").">";
	}
}

class mVarTextline extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function GetValue($raw = false)
	{
		return parent::GetValue($raw);
	}
	
	function Save($data)
	{
		return parent::Save($data);
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		return "<input id=\"$this->id\" name=\"$this->id\" type=\"text\" value=\"$this->value\">";
	}
}

class mVarText extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
	function GetValue($raw = false)
	{
		$value = parent::GetValue($raw);
		if ($raw)
			return $value;
			
		return nl2br($value);
	}
	
	function Save($data)
	{
		return parent::Save($data);
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		if (empty($this->extra))
			return "<textarea id=\"$this->id\" name=\"$this->id\">$this->value</textarea>";
		else
		{
			$parts = explode("x", $this->extra);
			return "<textarea id=\"$this->id\" name=\"$this->id\" cols=\"".$parts[0]."\" rows=\"".$parts[1]."\">$this->value</textarea>";
		}
	}
}

class mVarXhtml extends mVar
{
	function mVarText()
	{
		$this->mVar();
	}
	
		function GetValue($raw = false)
	{
		return parent::GetValue($raw);
	}
	
	function Save($data)
	{
		return parent::Save($data);
	}
	
	function Remove()
	{
		return parent::Remove();
	}
	
	function GetEdit($formname)
	{
		if (empty($this->extra))
			$text = "<textarea disabled id=\"v$this->id\" name=\"v$this->id\">$this->value</textarea>";
		else
		{
			$parts = explode("x", $this->extra);
			$text = "<textarea disabled id=\"v$this->id\" name=\"v$this->id\" cols=\"".$parts[0]."\" rows=\"".$parts[1]."\">$this->value</textarea>";
		}

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
		
		$query = "SELECT data,id FROM `values` WHERE object_id = '$this->object_id' AND var_id = '$this->id'";
		$result = mysql_query($query) or die("mVar::SetByArray: " . mysql_errno() . " " . mysql_error());
		
		if (mysql_num_rows($result) == 0)
		{
			$this->value = "";
			$this->value_id = 0;
		}
		else
		{
			$r = mysql_fetch_array($result, MYSQL_ASSOC);
			$this->value = $r['data'];
			$this->value_id = $r['id'];
		}
	}
	
	function GetVarsForObject($object_id, $class_name)
	{
		$query = "SELECT * FROM `vars` WHERE class_name = '$class_name' ORDER BY priority";
		$result = mysql_query($query) or die("mVar::GetVarsForObject: " . mysql_errno() . " " . mysql_error());
	
		$vars = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$class_name = "mVar".ucfirst($row['type']);
			$var = new $class_name();
			$row['object_id'] = $object_id;
			$var->SetByArray($row);
			$vars[] = $var;
		}
		return $vars;
	}
	
	function GetName($raw = false)
	{
		if ($raw)
			return $this->name;
		
		return ucfirst(str_replace("_", " ", $this->name));
	}
	
	function GetValue($raw = false)
	{
		if ($raw)
			return $this->value;
			
		$value = htmlspecialchars($this->value);
		
		return $value;
	}
	
	function Save($data)
	{
		if (empty($data))
			return $this->Remove();
	
		if ($this->value_id > 0)
		{
			$query = "UPDATE `values` SET data='$data', object_id='$this->object_id', var_id='$this->id' WHERE id = '$this->value_id'";
	
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
			$query = "INSERT INTO `values` (data, object_id, var_id) VALUES('$data', '$this->object_id', '$this->id')";
	
			$result = mysql_query($query);
			if (!$result)
			{
				$message = "<b>An error occured while inserting</b><br>";
				$message .= "<b>Table:</b> values<br>";
				$message .= "<b>Query:</b> $query<br>";
				$message .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
				$message .= "<b>Error:</b> " . mysql_error() . "<br>";
				echo $message;
				return false;
			}
			
			$this->value_id = mysql_insert_id();
		}
		
		return true;
	}
	
	function Remove()
	{
		$query = "DELETE FROM `values` WHERE object_id = '$this->object_id' AND var_id = '$this->id'";
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
	
	function GetEdit($formname)
	{
		return "<input id =\"$this->id\" name=\"$this->id\" type=\"text\" value=\"$this->value\">";
	}
	
	function GetValueByPath($path, $name, $raw = false)
	{
		$o = new mObject();
		$o->SetByPath($path);

		return $o->GetValue($name, $raw);
	}
}
?>
