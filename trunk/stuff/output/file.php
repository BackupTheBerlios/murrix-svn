<?

if (!isset($_GET['maxwidth']))
	@readfile($_GET['file']);
else
{
	$quality = 100;
	//if ($item->type == ALBUMITEM_TYPE_PICTURE)
	{
		$image = imagecreatefromjpeg($_GET['file']);
		$angle = $_GET['angle'];
		
		if (!empty($angle))
		{
			$angle = $angle % 360;
			if ($angle > 0)
			{
				$out = ImageRotate($image, $angle, 180);
				imagedestroy($image);
				$image = $out;
			}
		}
		
		if (isset($_GET['maxwidth']) && $_GET['maxwidth'] < imagesx($image))
		{
			$outputw = $_GET['maxwidth'];
			$outputh = imagesy($image) * ($_GET['maxwidth'] / imagesx($image));
			
			$output = imagecreatetruecolor($outputw, $outputh);
			
			imagecopyresampled($output, $image, 0, 0, 0, 0, imagesx($output), imagesy($output), imagesx($image), imagesy($image));
			
			imagedestroy($image);
			imagejpeg($output, '', $quality);
			imagedestroy($output);
		}
		else
		{
			imagejpeg($image, '', $quality);
			imagedestroy($image);
		}
	}
}

?>