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
			
			$thumb_id = $object->getVarValue("imagecache_id");
			
			if (!empty($thumb_id))
			{
				$thumbnail = new mThumbnail($thumb_id);
			
				if ($thumbnail->getRebuild())
				{
					$maxsize = 640;
					if ($thumbnail->CreateFromFile($filename, $pathinfo['extension'], $maxsize, 0, $angle))
					{
						if (!$thumbnail->Save())
							echo "Failed to create thumbnail<br>";
					}
				}

				list($width, $height, $type, $attr) = getimagesize($filename);

				?>
				<div class="image_control">

					<div class="title">
						<?=ucw(i18n("filesize")).":"?>
					</div>
					<?=DownloadSize(filesize($filename))?>
					<br/><br/>

					<div class="title">
						<?=ucw(i18n("geometry")).":"?>
					</div>
					<?=$width."x".$height?>
					<br/><br/>

					<div class="title">
						<?=ucw(i18n("angle")).":"?>
					</div>
					<?="$angle ".i18n("degrees")?>
					<br/><br/>

					<?
					$result = read_exif_data_raw($filename, 0);
					if (count($result))
					{
						if (isset($result['IFD0']['DateTime']))
						{?>
							<div class="title">
								<?=ucw(i18n("date and time")).":"?>
							</div>
							<?
								$date = trim($result['IFD0']['DateTime']);
								$dateparts = explode(" ", $date);
								echo str_replace(":", "-", $dateparts[0])." ".$dateparts[1];
							?>
							<br/><br/>
						<?}
						
						if (isset($result['SubIFD']['MakerNote']['ImageNumber']))
						{?>
							<div class="title">
								<?=ucw(i18n("image number")).":"?>
							</div>
							<?=trim($result['SubIFD']['MakerNote']['ImageNumber'])?>
							<br/><br/>
						<?}
						
						if (isset($result['SubIFD']['DigitalZoomRatio']))
						{?>
							<div class="title">
								<?=ucw(i18n("digital zoom ratio")).":"?>
							</div>
							<?=trim($result['SubIFD']['DigitalZoomRatio'])?>
							<br/><br/>
						<?}

						if (isset($result['IFD0']['Make']))
						{?>
							<div class="title">
								<?=ucw(i18n("camera brand")).":"?>
							</div>
							<?=trim($result['IFD0']['Make'])?>
							<br/><br/>
						<?}
							
						if (isset($result['IFD0']['Model']))
						{?>
							<div class="title">
								<?=ucw(i18n("camera model")).":"?>
							</div>
							<?=trim($result['IFD0']['Model'])?>
							<br/><br/>
						<?}
							
						if (isset($result['SubIFD']['FileSource']))
						{?>
							<div class="title">
								<?=ucw(i18n("camera type")).":"?>
							</div>
							<?=trim($result['SubIFD']['FileSource'])?>
							<br/><br/>
						<?}
					}
				
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
				?>
				</div>
				<?

				$thumbnail->Show();

				echo "<div class=\"clear\"></div>";
			}
			else
				echo ucf(i18n("file format not supported for inline view"));
		}
		else
			echo ucf(i18n("file format not supported for inline view"));
	?>
	</div>
</div>