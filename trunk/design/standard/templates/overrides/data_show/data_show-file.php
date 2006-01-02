<?

$filename = $object->getVarValue("file");
$pathinfo = pathinfo($filename);

?>
<div class="main">
	<div class="file">
	<?
		$type = getfiletype($pathinfo['extension']);
		if ($type == "image")
		{
			$angle = $object->getMeta("angle");
	
			if (empty($angle))
				$angle = GetFileAngle($filename);
	
			if ($angle < 0) $angle = 360+$angle;
			else if ($angle > 360) $angle = 360-$angle;
		?>
			<img src="?file=<?=$object->getNodeId()?>&maxwidth=640<?=($angle > 0 ? "&angle=$angle" : "")?>">
			<br/>
		<?
			if ($object->hasRight("edit"))
			{
				$angle_left = ($angle+90);
				if ($angle_left < 0) $angle_left = 360+$angle_left;
				else if ($angle_left > 360) $angle_left = 360-$angle_left;
	
				$angle_right = ($angle-90);
				if ($angle_right < 0) $angle_right = 360+$angle_right;
				else if ($angle_right > 360) $angle_right = 360-$angle_right;
	
				echo cmd(img(imgpath("rotate_left.png")), "Exec('show','zone_main', Hash('meta', 'angle', 'value', '$angle_left', 'rebuild_thumb', '1'))");
				echo "&nbsp;";
				echo cmd(img(imgpath("rotate_right.png")), "Exec('show','zone_main', Hash('meta', 'angle', 'value', '$angle_right', 'rebuild_thumb', '1'))");
			}
		}
		else
			echo ucf(i18n("file format not supported for inline view"));
	?>
	</div>
</div>