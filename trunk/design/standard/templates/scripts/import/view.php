<?
echo compiletpl("scripts/show/tabs", array("view"=>"import"), $object);

echo compiletpl("title/big", array("left"=>img(geticon("menu"))."&nbsp;".ucf(i18n("import"))));

echo compiletpl("scripts/import/tabs", array("view"=>$args['view']), $object);

global $abspath;

switch ($args['view'])
{
	case "custom":
	echo "<div id=\"zone_import_custom\">";
	echo compiletplWithOutput("scripts/import/custom", $args, $object);
	echo "</div>";
	break;
	
	case "upload":
	$chroot = "$abspath/upload";
	
	$path = empty($args['path']) ? "/" : urldecode($args['path']);
	$fullpath = extractPath("$chroot$path");
	
	$testpath = substr($fullpath, 0, strlen($chroot));
	if ($testpath != $chroot)
	{
		$system->addAlert(ucf(i18n("this path is not allowed")));
		$args['path'] = "/";
	}
	else
		$args['path'] = empty($args['path']) ? "/" : urldecode($args['path']);
	
	echo compiletplWithOutput("scripts/import/upload", $args, $object);
	break;
	
	case "files":
	echo compiletplWithOutput("scripts/import/files", $args, $object);
	break;
	
	default:
	case "xml":
	echo compiletplWithOutput("scripts/import/xml", $args, $object);
	break;
}

echo compiletpl("scripts/import/log", array());
?>