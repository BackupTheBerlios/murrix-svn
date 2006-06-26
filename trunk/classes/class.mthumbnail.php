<?

class mThumbnail
{
	var $id;
	var $data;
	var $created;
	var $width;
	var $height;
	var $value_id;
	var $type;

	function mThumbnail($inid = 0)
	{
		if ($inid <= 0)
			return;

		global $db_prefix;

		$query = "SELECT * FROM `".$db_prefix."thumbnails` WHERE `id`='$inid'";

		$result = mysql_query($query) or die("mThumbnail: " . mysql_errno() . " " . mysql_error());
		$this->SetByArray(mysql_fetch_array($result, MYSQL_ASSOC));
	}
	
	function setBySize($value_id, $maxwidth, $maxheight, $angle = 1000)
	{
		global $db_prefix, $abspath;

		$query = "SELECT * FROM `".$db_prefix."thumbnails` WHERE `value_id`='$value_id' AND (`width`='$maxwidth' OR `height`='$maxheight') LIMIT 1";

		$result = mysql_query($query) or die("setBySize: " . mysql_errno() . " " . mysql_error());
		
		if (mysql_num_rows($result) > 0)
		{
			$this->SetByArray(mysql_fetch_array($result, MYSQL_ASSOC));
			return true;
		}
		else if (getSetting("INSTANTTHUMBS", "true") == "true")
		{
			$query = "SELECT `data` FROM `".$db_prefix."values` WHERE `id`='$value_id'";

			$result = mysql_query($query) or die("setBySize: " . mysql_errno() . " " . mysql_error());
			$data = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$filename = $data['data'];
			
			$extension = pathinfo($filename, PATHINFO_EXTENSION);
		
			$filename = "$abspath/files/$value_id.$extension";
			$type = getfiletype($extension);
		
			$data = "";
			if ($type == "image")
			{
				if ($angle == 1000)
					$angle = GetFileAngle($filename);
	
				if ($this->CreateFromFile($filename, $extension, $maxwidth, $maxheight, $angle))
				{
					$this->value_id = $value_id;
					if (!$this->Save())
						return false;
						
					return true;
				}
			}
		}
		
		return false;
	}
	
	function SetByArray($array)
	{
		$this->id = $array['id'];
		$this->created = $array['created'];
		$this->width = $array['width'];
		$this->height = $array['height'];
		$this->value_id = $array['value_id'];
		$this->type = $array['type'];
	}
	
	function Show($return = false, $title = "", $style = "")
	{
		$str = "<img style=\"$style\" title=\"$title\" alt=\"$title\" src=\"?thumbnail=$this->id\" class=\"image-border\" width=\"$this->width\" height=\"$this->height\"/>";

		if ($return)
			return $str;

		echo $str;
	}

	function Output()
	{
		global $abspath;
		$filename = "$abspath/thumbnails/".$this->id.".jpg";
		
		if (!file_exists($filename))
		{
			$this->setRebuild();
			return;
		}
		
		header("Content-type: " . image_type_to_mime_type($this->type));
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', strtotime($this->created)).' GMT');
		header("Content-Length: ".filesize($filename));
		@readfile($filename);
		return;
	}

	function setRebuild()
	{
		$this->width = 0;
		$this->height = 0;
		$this->type = 0;
		$this->data = "";
		$this->Save();
	}

	function getRebuild()
	{
		return ($this->width == 0 || $this->height == 0 || $this->type == 0);
	}

	function duplicate()
	{
		global $abspath;
		$filename = "$abspath/thumbnails/".$this->id.".jpg";
		
		$image = imagecreatefromjpeg($filename);
		
		$this->id = 0;
		
		ob_start(); // Start capturing stdout. 
		imagejpeg($image); // As though output to browser.
		$binaryThumbnail = ob_get_contents(); // the raw jpeg image data. 
		ob_end_clean(); // Dump the stdout so it does not screw other output.

		$this->data = $binaryThumbnail;
	}
	
	function CreateFromFile($filename, $extension, $maxsizex, $maxsizey = 0, $angle = 0)
	{
		switch (strtolower($extension))
		{
			case "jpeg":
			case "jpg":
			$image = imagecreatefromjpeg($filename);
			if (empty($image)) return false;
			break;

			case "png":
			$image = imagecreatefrompng($filename);
			if (empty($image)) return false;
			break;

			case "gif":
			$image = imagecreatefromgif($filename);
			if (empty($image)) return false;
			break;

			case "bmp":
			$image = imagecreatefromwbmp($filename);
			if (empty($image)) return false;
			break;

			default:
				return false;
		}

		if ($angle < 0) $angle = 360+$angle;
		else if ($angle >= 360) $angle = 360-$angle;

		if ($angle > 0)
		{
			$out = ImageRotate($image, $angle, 180);
			imagedestroy($image);
			$image = $out;
		}
		
		if (imagesy($image) > imagesx($image) && $maxsizey > 0)// höjden = maxsize;
		{
			$h = $maxsizey;
			$w = imagesx($image) * ($maxsizey / imagesy($image));
		}
		else//bredden = maxsize
		{
			$h = imagesy($image) * ($maxsizex / imagesx($image));
			$w = $maxsizex;
		}

		$output = imagecreatetruecolor($w, $h);

		//imageCopyResampleBicubic($output, $image, 0, 0, 0, 0, imagesx($output), imagesy($output), imagesx($image), imagesy($image));
		imagecopyresampled($output, $image, 0, 0, 0, 0, imagesx($output), imagesy($output), imagesx($image), imagesy($image));
		
		imagedestroy($image);
	
		ob_start(); // Start capturing stdout. 
		imagejpeg($output); // As though output to browser.
		$binaryThumbnail = ob_get_contents(); // the raw jpeg image data. 
		ob_end_clean(); // Dump the stdout so it does not screw other output.

		$this->type = IMAGETYPE_JPEG;
		$this->data = $binaryThumbnail;
		$this->created = date("Y-m-d H:i:s");
		$this->width = $w;
		$this->height = $h;

		return true;
	}
	
	function Save()
	{
		global $db_prefix, $abspath;

		if ($this->id == 0) // Add
		{
			$query = "INSERT INTO `".$db_prefix."thumbnails` (created, width, height, value_id, type) VALUES('$this->created','$this->width','$this->height','$this->value_id','$this->type')";

			$result = mysql_query($query);
			if (!$result)
			{
				$string = "<b>An error occured while inserting</b><br>";
				$string .= "<b>Table:</b> `".$db_prefix."thumbnails`<br>";
				$string .= "<b>Query:</b> $query<br>";
				$string .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
				$string .= "<b>Error:</b> " . mysql_error() . "<br>";
				echo $string;
				return false;
			}

			$this->id = mysql_insert_id();

			if (!empty($this->data))
			{
				$file = fopen("$abspath/thumbnails/".$this->id.".jpg", "w");
				fwrite($file, $this->data);
				fclose($file);
			}
			
			return true;
		}
		else // Save
		{
			$query = "UPDATE `".$db_prefix."thumbnails` SET created='$this->created', width='$this->width', height='$this->height', value_id='$this->value_id', type='$this->type' WHERE id = '$this->id'";

			$result = mysql_query($query);
			if (!$result)
			{
				$string = "<b>An error occured while updateing</b><br>";
				$string .= "<b>Table:</b> `".$db_prefix."thumbnails`<br>";
				$string .= "<b>Query:</b> $query<br>";
				$string .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
				$string .= "<b>Error:</b> " . mysql_error() . "<br>";
				echo $string;
				return false;
			}

			@unlink("$abspath/thumbnails/".$this->id.".jpg");

			if (!empty($this->data))
			{
				$file = fopen("$abspath/thumbnails/".$this->id.".jpg", "w");
				fwrite($file, $this->data);
				fclose($file);
			}
			
			return true;
		}
	}
	
	function Remove()
	{
		if ($this->id == 0)
			return true;
			
		global $db_prefix, $abspath;
			
		$query = "DELETE FROM `".$db_prefix."thumbnails` WHERE id = '$this->id'";
		
		$result = mysql_query($query);
		if (!$result)
		{
			$string = "<b>An error occured while deleting</b><br>";
			$string .= "<b>Table:</b> `".$db_prefix."thumbnails`<br>";
			$string .= "<b>Query:</b> $query<br>";
			$string .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
			$string .= "<b>Error:</b> " . mysql_error() . "<br>";
			echo $string;
			return false;
		}

		@unlink("$abspath/thumbnails/".$this->id.".jpg");
		
		return true;
	}
}

function getThumbnail($value_id, $maxwidth, $maxheight, $angle = 1000)
{
	$thumbnail = new mThumbnail();
	
	if (!$thumbnail->setBySize($value_id, $maxwidth, $maxheight, $angle))
		return false;
		
	return $thumbnail;
}

function delThumbnails($value_id)
{
	global $db_prefix;

	$query = "SELECT `id` FROM `".$db_prefix."thumbnails` WHERE `value_id`='$value_id'";

	$result = mysql_query($query) or die("delThumbnails: " . mysql_errno() . " " . mysql_error());
	
	while ($data = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$thumbnail = new mThumbnail($data['id']);
		$thumbnail->Remove();
	}
}

?>
