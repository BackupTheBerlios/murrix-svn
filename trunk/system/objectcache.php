<?

function getObjectFromCache($object_id)
{
	if (isset($_SESSION['murrix']['objectcache'][$object_id]))
		return $_SESSION['murrix']['objectcache'][$object_id];

	global $abspath;

	$filename = "$abspath/cache/$object_id.obj";
	if (file_exists($filename))
	{
		$handle = @fopen($filename, "r");
		if ($handle)
		{
			$contents = fread($handle, filesize($filename));
			fclose($handle);

			$_SESSION['murrix']['objectcache'][$object_id] = unserialize($contents);
			return $_SESSION['murrix']['objectcache'][$object_id];
		}
	}
	
	return false;
}

function addObjectToCache($object)
{
	$_SESSION['murrix']['objectcache'][$object->getId()] = $object;

	global $abspath;

	$filename = "$abspath/cache/".$object->getId().".obj";
	
	if (is_writable("$abspath/cache/"))
	{
	
		// In our example we're opening $filename in append mode.
		// The file pointer is at the bottom of the file hence
		// that's where $somecontent will go when we fwrite() it.
		if ($handle = fopen($filename, 'w'))
		{
			fwrite($handle, serialize($object));
			fclose($handle);
		}
	}
}

function delObjectFromCache($object_id)
{
	unset($_SESSION['murrix']['objectcache'][$object_id]);

	global $abspath;

	$filename = "$abspath/cache/$object_id.obj";
	@unlink($filename);
}

?>