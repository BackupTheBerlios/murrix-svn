<table class="invisible" style="width: 100%; margin-bottom: 5px;" cellspacing="0">
	<tr>
	<?
		$value_id = $object->resolveVarName("file");
		$type = getfiletype(pathinfo($object->getVarValue("file"), PATHINFO_EXTENSION));
		
		$data = "";
		if ($type == "image")
		{
			$maxsize = getSetting("IMGSIZE", 640);
			$angle = $object->getMeta("angle");
			
			if (empty($angle))
				$angle = GetFileAngle($filename);
				
			$thumbnail = getThumbnail($value_id, $maxsize, 0, $angle);
			
			if ($thumbnail !== false)
			{
				$_SESSION['murrix']['rightcache']['thumbnail'][] = $thumbnail->id;
				$data .= $thumbnail->Show(true);
			}
			else
				$data .= ucf(i18n("file format not supported for inline view"));
		}
		//else
		//	$data .= ucf(i18n("file format not supported for inline view"));

		$text = $object->getVarValue("description");
		if (!empty($text))
		{
			$data .= "<br/><br/>";
			$data .= $object->getVarValue("description");
			$data .= "<br/><br/>";
		}

		if (!empty($data))
		{
		?>
			<td style="margin-right: 5px;">
				<div class="file">
					<?=$data?>
				</div>
			</td>
		<?
		}
		?>
		
		<td style="width: 100%">
			<div class="file_panel">
				<div class="title">
					<?=ucw(i18n("links"))?>
				</div>
				<a href="?file=<?=$object->getNodeId()?>&download=1"><?=img(geticon("download"))." ".ucf(i18n("download"))?></a>
				<a target="top" href="?file=<?=$object->getNodeId()?>"><?=img(geticon(getfiletype($pathinfo['extension'])))." ".ucf(i18n("open orginal"))?></a>
				
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
						
					?>
					<div class="title">
						<?=ucw(i18n("rotate"))?>
					</div>
					<div style="float: left; width: 50%;">
						<?=cmd(img(imgpath("rotate_left.png")), "exec=show&node_id=".$object->getNodeId()."&meta=angle&value=$angle_left&rebuild_thumb=$value_id")?>
					</div>
					<div style="float: right; width: 50%;">
						<?=cmd(img(imgpath("rotate_right.png")), "exec=show&node_id=".$object->getNodeId()."&meta=angle&value=$angle_right&rebuild_thumb=$value_id")?>
					</div>
					<div class="clear"></div>
					<?
				}
				
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
			</div>

		</td>
	</tr>
</table>