<div class="main">
	<?
		$value_id = $object->resolveVarName("file");
		$filename = $object->getVarValue("file");
		$type = getfiletype(pathinfo($filename, PATHINFO_EXTENSION));
		
		if ($type == "image")
		{
			$result = read_exif_data_raw($filename, 0);
			$maxsize = getSetting("IMGSIZE", 640);
			$angle = $object->getMeta("angle");
			
			if (empty($angle))
				$angle = GetFileAngle($filename);
				
			$thumbnail = getThumbnail($value_id, $maxsize, 0, $angle);
			
			if ($thumbnail !== false)
			{
				$_SESSION['murrix']['rightcache']['thumbnail'][] = $thumbnail->id;
				$data = $thumbnail->Show(true);
			}
			else
				$data = img(geticon($type, 128));
		}
		else
			$data = img(geticon($type, 128));
			
		$_SESSION['murrix']['rightcache']['file'][] = $value_id;
		?>
	
		<a target="top" href="?file=<?=$value_id?>"><?=$data?></a><br/>
		
		<?
		if ($type == "image" && $object->hasRight("write"))
		{
			$angle_left = ($angle+90);
			if ($angle_left < 0) $angle_left = 360+$angle_left;
			else if ($angle_left > 360) $angle_left = 360-$angle_left;

			$angle_right = ($angle-90);
			if ($angle_right < 0) $angle_right = 360+$angle_right;
			else if ($angle_right > 360) $angle_right = 360-$angle_right;

			if ($angle_left == 0)
				$angle_left = 360;

			if ($angle_right == 0)
				$angle_right = 360;
				
			echo cmd(img(imgpath("rotate_left.png")), "exec=show&node_id=".$object->getNodeId()."&meta=angle&value=$angle_left&rebuild_thumb=$value_id");
			?> <a href="?file=<?=$value_id?>&download=1"><?=img(geticon("download"))?></a> <?
			echo cmd(img(imgpath("rotate_right.png")), "exec=show&node_id=".$object->getNodeId()."&meta=angle&value=$angle_right&rebuild_thumb=$value_id");
			
		}
		?>
</div>
<div class="file_panel">
	<table class="invisible" width="100%" cellspacing="0">
		<tr>
			<td style="padding-right: 5px;">
				<div class="header">
					<?=ucf(i18n("description"))?>
				</div>
				<div style="padding-top: 5px;">
					<?=$object->getVarValue("description")?>
				</div>
			</td>
			
			<td style="width: 200px;">
				<div class="header">
					<?=ucf(i18n("information"))?>
				</div>
				
				<?
				if ($type == "image" && isset($result['IFD0']['DateTime']))
				{?>
					<div class="title">
						<?=ucw(i18n("date and time"))?>
					</div>
					<?
						$date = trim($result['IFD0']['DateTime']);
						$dateparts = explode(" ", $date);
						echo str_replace(":", "-", $dateparts[0])." ".$dateparts[1];
					?>
				<?}
				?>
				<div class="title">
					<?=ucw(i18n("filesize"))?>
				</div>
				<?=DownloadSize(filesize($filename))?>
		
				<div class="title">
					<?=ucw(i18n("original filename"))?>
				</div>
				<?=$object->getVarValue("file", true)?>

				<?
				if ($type == "image")
				{
					list($width, $height, $type2, $attr) = getimagesize($filename);
					?>
					<div class="title">
						<?=ucw(i18n("geometry"))?>
					</div>
					<?=$width."x".$height?>
				
		
					<div class="title">
						<?=ucw(i18n("angle"))?>
					</div>
					<?="$angle ".i18n("degrees")?>
		
					<?
					if (isset($result['SubIFD']['DigitalZoomRatio']))
					{?>
						<div class="title">
							<?=ucw(i18n("digital zoom ratio"))?>
						</div>
						<?=trim($result['SubIFD']['DigitalZoomRatio'])?>
					<?}
	
					if (isset($result['SubIFD']['MakerNote']['ImageNumber']))
					{?>
						<div class="title">
							<?=ucw(i18n("image number"))?>
						</div>
						<?=trim($result['SubIFD']['MakerNote']['ImageNumber'])?>
					<?}
	
					if (isset($result['IFD0']['Make']))
					{?>
						<div class="title">
							<?=ucw(i18n("camera brand"))?>
						</div>
						<?=trim($result['IFD0']['Make'])?>
					<?}
	
					if (isset($result['IFD0']['Model']))
					{?>
						<div class="title">
							<?=ucw(i18n("camera model"))?>
						</div>
						<?=trim($result['IFD0']['Model'])?>
					<?}
	
					if (isset($result['SubIFD']['FileSource']))
					{?>
						<div class="title">
							<?=ucw(i18n("camera type"))?>
						</div>
						<?=trim($result['SubIFD']['FileSource'])?>
					<?}
				}
			?>
			</td>
		</tr>
	</table>
</div>
