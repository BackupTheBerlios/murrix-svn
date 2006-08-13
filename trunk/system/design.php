<?

function drawImageRegions($imageheight, $imagewidth, $mapname, $array)
{
	ob_start();
	
	foreach ($array as $region)
	{
		list($id, $x, $y, $w, $h, $text) = $region;
		?>
		<div onmouseout="this.style.display='none';" onmouseover="this.style.display='block';" id="<?=$id?>" class="image_region" style="display: none;  left: <?=$x?>px; top: <?=($y-$imageheight-1)?>px; position: relative; width: <?=$w?>px; height: <?=$h?>px; z-index: 10;"><div class="border"><div id="inner_region" class="text"><?=$text?></div></div></div><?
	}
	
	?><map name="<?=$mapname?>"><?
	
	foreach ($array as $region)
	{
		list($id, $x, $y, $w, $h) = $region;
		?><area shape="RECT" coords="<?=$x?>,<?=$y+1?>,<?=($w+$x-1)?>,<?=($h+$y)?>" href="javascript:void(null)" onmouseover="document.getElementById('<?=$id?>').style.display='block';"/><?
	}

	?></map><?
	
	return ob_get_end();
}

function ucw($text)
{
	return ucwords($text);
}

function ucf($text)
{
	$buffer = ucfirst($text);

	if ($buffer{0} == 'å')
		$buffer{0} = 'Å';
	else if ($buffer{0} == 'ä')
		$buffer{0} = 'Ä';
	else if ($buffer{0} == 'ö')
		$buffer{0} = 'Ö';

	return $buffer;
}

function runjs($name, $js)
{
	return "<a href=\"javascript:void(null)\" onclick=\"$js\" onkeyup=\"$js\">$name</a>";
}

function cmd($name, $cmd, $args = null)
{
	if (is_string($args))
		$args = array("class" => $args);
	else if (!is_array($args))
		$args = array();

	$args['class'] = "cmd ".$args['class'];
	
	$string = "";
	foreach ($args as $key => $value)
		$string .= "$key=\"$value\" ";
		
	$cmd = htmlspecialchars($cmd);
	
	return "<a href=\"?$cmd\" $string>$name</a>";
}

function imgpath($append = "")
{
	global $wwwpath;
	return "$wwwpath/design/".$_SESSION['murrix']['site']."/images/$append";
}

function img($img, $title = "", $style = "", $id = "")
{
	global $abspath, $wwwpath;

	$parent_path = substr($abspath, 0, strlen($abspath)-strlen($wwwpath));
	list($width, $height, $type, $attr) = getimagesize("$parent_path/$img");
	
	if (!empty($id))
		$id = " id='$id'";

	return "<img$id src=\"$img\" title=\"$title\" alt=\"$title\" style=\"width: ".$width."px; height: ".$height."px;$style\"/>";
}

function getfiletype($extension)
{
	switch (strtolower($extension))
	{
		case "pdf":
		return "pdf";

		case "ps":
		return "postscript";

		case "jpeg":
		case "jpg":
		case "png":
		case "gif":
		case "bmp":
		return "image";

		case "wav":
		case "ogg":
		case "mp3":
		case "ra":
		case "ram":
		return "sound";

		case "mpg":
		case "mpeg":
		case "wmv":
		case "avi":
		case "divx":
		case "mov":
		case "rm":
		return "video";

		case "txt":
		case "nfo":
		return "text";

		case "doc":
		case "odt":
		case "sxw":
		case "rtf":
		return "document";

		case "xls":
		case "ods":
		case "sxc":
		return "spreadsheet";

		case "odp":
		case "ppt":
		case "sxi":
		return "presentation";

		case "phps":
		case "php":
		return "source_php";

		case "cpp":
		return "source_cpp";

		case "c":
		return "source_c";

		case "h":
		return "source_h";

		case "java":
		case "jar":
		return "source_java";

		case "pl":
		return "source_pl";

		case "exe":
		case "msi":
		case "com":
		return "wine";

		case "html":
		case "htm":
		return "html";

		case "tar":
		case "gz":
		case "bz2":
		case "tar.gz":
		case "tar.bz2":
		case "zip":
		case "rar":
		case "arg":
		return "archive";

		case "rpm":
		return "rpm";

		case "deb":
		return "deb";

		case "iso":
		return "cd";

		case "img":
		return "cd";

		case "sh";
		return "shell";
	}

	return "file";
}

function geticon($name, $size = 16, $ext = "png")
{
	global $abspath, $wwwpath;
	$name = strtolower($name);
	
	if (file_exists("$abspath/design/".$_SESSION['murrix']['site']."/icons/$size/$name.$ext"))
		return "$wwwpath/design/".$_SESSION['murrix']['site']."/icons/$size/$name.$ext";

	if (file_exists("$abspath/design/standard/icons/$size/$name.png"))
		return "$wwwpath/design/standard/icons/$size/$name.png";

	return "$wwwpath/design/standard/icons/$size/broken.png";
}

function getjs()
{
	global $abspath, $wwwpath;
	$files = GetSubfiles("$abspath/design/".$_SESSION['murrix']['site']."/javascripts");

	for ($n = 0; $n < count($files); $n++)
		$files[$n] = "$wwwpath/design/".$_SESSION['murrix']['site']."/javascripts/".$files[$n];

	return $files;
}

function getcss()
{
	if (isset($_GET['nocss']))
		return array();
		
	global $abspath, $wwwpath;
	$files = GetSubfiles("$abspath/design/".$_SESSION['murrix']['site']."/stylesheets");

	$files2 = array();
	for ($n = 0; $n < count($files); $n++)
	{
		$paths = pathinfo($files[$n]);
		if (strtolower($paths['extension']) == "css")
			$files2[] = "$wwwpath/design/".$_SESSION['murrix']['site']."/stylesheets/".$files[$n];
	}

	return $files2;
}

function _gettemplateoverride($filename, $object, $site, $path)
{
	global $abspath;

	$tpl = "";
	if (file_exists("$abspath/design/$site/templates/override.inc.php")) // We have possible overrides, check these first
	{
		include("$abspath/design/$site/templates/override.inc.php");

		if (isset($templates_override[$filename]))// We have possible overrides for this files
		{
			$rank_m = 0;
			foreach ($templates_override[$filename] as $template)// Loop through possible templates
			{
				$rank = 0;
				if (isset($template['match']))// Do we have a matchcondition for this template
				{
					foreach ($template['match'] as $key => $value) // Loop conditions
					{
						switch ($key)
						{
						case "class":
							if ($object->getClassName() == $value)
								$rank++; // If condition matches then this is a possible template, increase rank of template
							break;
							
						case "path":
							if ($object->getPath() == $value)
								$rank = 100000; // Path should overrid everything else
							break;
						}
					}
				}
				
				if ($rank > $rank_m)// is this better ranked then the last
				{
					if (file_exists("$path/design/$site/templates/".$template['filename']))
						$tpl = "$path/design/$site/templates/".$template['filename'];
					else
					{
						if ($site != "standard")
						{
							if (file_exists("$path/design/standard/templates/".$template['filename']))
								$tpl = "$path/design/standard/templates/".$template['filename'];
						}
					}
				}
			}
		}
	}

	return $tpl;
}

function gettpl_path($template, $object = null, $path = "")
{
	global $abspath;

	$filename = "$template.php";

	$tpl = _gettemplateoverride($filename, $object, $_SESSION['murrix']['site'], $path);

	if (empty($tpl)) // No override template found, check for default
	{
		if (file_exists("$abspath/design/".$_SESSION['murrix']['site']."/templates/$filename")) // Found default template
			$tpl = "$path/design/".$_SESSION['murrix']['site']."/templates/$filename";
		else // Did not find default template using standard instead
		{
			$tpl = _gettemplateoverride($filename, $object, "standard", $path);
			if (empty($tpl))
				$tpl = "$path/design/standard/templates/$filename";
		}
	}

	return $tpl;
}

function gettpl($template, $object = null)
{
	global $abspath;

	return gettpl_path($template, $object, $abspath);
}

function gettpl_www($template, $object = null)
{
	global $wwwpath;

	return gettpl_path($template, $object, $wwwpath);
}

function compiletpl($template, $args, $object = null)
{
	ob_start();
	
	include(gettpl($template, $object));

	return ob_get_end();
}

function compiletplWithOutput($template, &$args, $object = null)
{
	ob_start();
	
	include(gettpl($template, $object));

	return ob_get_end();
}

function i18n($text, $language = "")
{
	if (empty($language))
		$language = $_SESSION['murrix']['language'];

	if (isset($_SESSION['murrix']['translations'][$language][$text]))
		return $_SESSION['murrix']['translations'][$language][$text];

	if (isset($_SESSION['murrix']['lang_debug']) && $_SESSION['murrix']['lang_debug'] === true)
	{
		global $abspath;
		$translation_path = "$abspath/design/".$_SESSION['murrix']['theme'];
	
		if (is_writable($translation_path))
		{
			$file = fopen("$translation_path/$language.missing.php", "a+");
			fwrite($file, "\$translation['$text'] = \"\";\n");
			fclose($file);
		}
	}

	return $text;
}

?>