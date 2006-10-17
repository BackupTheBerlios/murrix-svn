<div class="main">
	<div class="container">
		<center>
		<?
			global $wwwpath;
		
			$parent = new mObject(getNode(getParentPath($object->getPathInTree())));
			
			$invert = "";
			if ($parent->getMeta("sort_direction", "") == "asc")
				$invert = "!";
			
			$family = fetch("FETCH node WHERE link:node_top='".$parent->getNodeId()."' AND link:type='sub' AND !property:class_name='comment' AND !property:class_name='poll_answer' NODESORTBY property:version SORTBY $invert".$parent->getMeta("sort_by", "property:name"));
			
			$family = getReadable($family);
			
			$prev = false;
			$next = false;
			
			for ($n = 0; $n < count($family); $n++)
			{
				if ($family[$n]->getNodeId() == $object->getNodeId())
				{
					if ($n > 0)
						$prev = $family[$n-1];
						
					if ($n < count($family)-1)
						$next = $family[$n+1];
				
					break;
				}
			}
			$n++;
		
			$value_id = $object->resolveVarName("file");
			$filename = $object->getVarValue("file");
			$type = getfiletype(pathinfo($filename, PATHINFO_EXTENSION));
			
			$angle = -1;
			$maxsize = getSetting("IMGSIZE", 640);
			
			if ($type == "image")
			{
				$result = read_exif_data_raw($filename, 0);
				
				$angle = $object->getMeta("angle", "");
				
				$thumbnail = getThumbnail($value_id, $maxsize, $maxsize, $angle);
				
				if ($object->hasRight("write"))
				{
					if ($angle == "")
						$angle = 0;
					
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
						
					$rotate_left = cmd(img(geticon("rotate_ccw")), "exec=show&node_id=".$object->getNodeId()."&meta=angle&value=$angle_left&rebuild_thumb=$value_id")." ";
					
					$rotate_right = cmd(img(geticon("rotate_cw")), "exec=show&node_id=".$object->getNodeId()."&meta=angle&value=$angle_right&rebuild_thumb=$value_id")." ";
				}
				
				if ($thumbnail !== false)
				{
					$_SESSION['murrix']['rightcache']['thumbnail'][] = $thumbnail->id;
					//$data = "<iframe style=\"margin: 0; padding: 0; border: 1px solid black;\" src=\"?thumbnail=$thumbnail->id\" class=\"image-border\" width=\"100%\" height=\"$thumbnail->height\"/>";
					
					$regions = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='image_region' NODESORTBY property:version SORTBY property:name");
					
					$rlist = array();
					
					foreach ($regions as $region)
					{
						$related = fetch("FETCH node WHERE link:node_id='".$region->getNodeId()."' AND link:type='data' NODESORTBY property:version SORTBY property:name");
						
						$text = $region->getVarValue("text");
						if (!empty($text))
							$text .= "<br/>";
						
						$cmdags = array("onmouseover" => "document.getElementById('region".$region->getNodeId()."').style.display='block';");
						
						foreach ($related as $rdata)
							$text .= cmd(img(geticon($rdata->getIcon()))."&nbsp;".$rdata->getName(), "exec=show&node_id=".$rdata->getNodeId(), $cmdags);
					
						///FIXME: Check thumbnailsize and do conversion of coords if not matching region specified size!!!
						list($x, $y, $w, $h) = explode(",", $region->getVarValue("params"));
						$rlist[] = array("region".$region->getNodeId(), $x, $y, $w, $h, $text);
					}
					
					//$data = "<div style=\"text-align: left; overflow: hidden; width: {$thumbnail->width}px; height: {$thumbnail->height}px;\">";
				/*	PrintPre($rlist);
					echo "<br>";
					echo $thumbnail->height."x".$thumbnail->width;*/
					if (count($rlist) > 0)
					{
						$data .= "<img id=\"id{$thumbnail->id}\" class=\"image-border\" style=\"width: {$thumbnail->width}px; height: {$thumbnail->height}px;\" usemap=\"#map{$thumbnail->id}\" src=\"$wwwpath/backends/thumbnail.php?id={$thumbnail->id}&created={$thumbnail->created}\"/>";
						$data .= drawImageRegions($thumbnail->height, $thumbnail->width, "map".$thumbnail->id, $rlist);
					}
					else
						$data .= "<img id=\"id{$thumbnail->id}\" class=\"image-border\" style=\"width: {$thumbnail->width}px; height: {$thumbnail->height}px;\" src=\"$wwwpath/backends/thumbnail.php?id={$thumbnail->id}&created={$thumbnail->created}\"/>";
						
					//$data .= "</div>";
				}
				else
					$data = img(geticon($type, 128));
					
				list($width, $height, $type2, $attr) = @getimagesize($filename);
				
				$datetime = "";
				if (isset($result['IFD0']['DateTime']))
					$datetime = trim($result['IFD0']['DateTime']);
				else if (isset($result['SubIFD']['DateTimeOriginal']))
					$datetime = trim($result['SubIFD']['DateTimeOriginal']);
				
				if (!empty($datetime))
				{
					list($date, $time) = explode(" ", $datetime);
					$datetime = str_replace(":", "-", $date)." $time";
				}
				
			}
			else
				$data = img(geticon($type, 128));
				
			$_SESSION['murrix']['rightcache']['file'][] = $value_id;
			?>
			<div class="container">
			<?
				echo ucf(i18n("file"))." $n ".i18n("of")." ".count($family)."<br/>";
				if ($prev !== false)
					echo cmd(img(imgpath("left.png")), "exec=show&node_id=".$prev->getNodeId());
				else
					echo img(imgpath("gray_left.png"));
					
				echo " ".cmd(img(imgpath("up.png")), "exec=show&node_id=".$parent->getNodeId())." ";
				
				if ($next !== false)
					echo cmd(img(imgpath("right.png")), "exec=show&node_id=".$next->getNodeId());
				else
					echo img(imgpath("gray_right.png"));
			?>
			</div>
			<?/*<a target="top" href="?file=<?=$value_id?>"><?=$data?></a>*/?>
			
			<?=$data?>
			
			<table cellspacing="0" style="width: <?=$maxsize?>px; font-style: italic; font-size: 11px;">
				<tr>
					<? if (!empty($datetime)) { ?>
					<td>
						<?=$datetime?>
					</td>
					<?
					}
					?>
					<td style="text-align: right">
						<?=DownloadSize(@filesize($filename))?>
						<a href="?file=<?=$value_id?>&download=1"><?=img(geticon("download"))." ".ucf(i18n("download"))?></a>
					</td>
				</tr>
			</table>
			
			<?
			$description = $object->getVarValue("description");
			if (!empty($description))
				echo "$description<br/><br/>";
			?>
			
			<a href="javascript:noneDisplay('settings');invertDisplay('details');">[<?=ucf(i18n("details"))?>]</a>
			<? if ($object->hasRight("write")) { ?>
				<a href="javascript:noneDisplay('details');invertDisplay('settings');">[<?=ucf(i18n("settings"))?>]</a>
			<? } ?>
			<div id="details" style="display: none;">
				<hr/>
				<table style="width: 100%; text-align: center;">
					<tr>
						<?
						if (isset($result['SubIFD']['ApertureValue'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("aperture"))?></div>
							<?=trim($result['SubIFD']['ApertureValue'])?>
						</td>
						<?
						}
						if (isset($result['SubIFD']['FocalLength'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("focal length"))?></div>
							<?=trim($result['SubIFD']['FocalLength'])?>
						</td>
						<?
						}
						if (isset($result['SubIFD']['ShutterSpeedValue'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("shutter speed"))?></div>
							<?=trim($result['SubIFD']['ShutterSpeedValue'])?>
						</td>
						<?
						}
						if (isset($result['SubIFD']['ExposureTime'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("exposure time"))?></div>
							<?=trim($result['SubIFD']['ExposureTime'])?>
						</td>
						<?
						}
						if (isset($result['SubIFD']['ExposureBiasValue'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("exposure bias"))?></div>
							<?=trim($result['SubIFD']['ExposureBiasValue'])?>
						</td>
						<?
						}
						if (isset($result['SubIFD']['Flash'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("flash"))?></div>
							<?=ucf(i18n(strtolower(trim($result['SubIFD']['Flash']))))?>
						</td>
						<?
						}
						if (isset($result['IFD0']['Orientation'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("orientation"))?></div>
							<?=trim($result['IFD0']['Orientation'])?>
						</td>
						<?
						}
						?>
					</tr>
				</table>
				<table style="width: 100%; text-align: center;">
					<tr>
						<?
						if (!empty($width)) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("geometry"))?></div>
							<?=$width."x".$height?>
						</td>
						<?
						}
						if (!empty($filename)) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("filename"))?></div>
							<?=$object->getVarValue("file", true)?></a>
						</td>
						<?
						}
						if (isset($result['SubIFD']['MakerNote']['ImageNumber'])) { ?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("image number"))?></div>
							<?=trim($result['SubIFD']['MakerNote']['ImageNumber'])?>
						</td>
						<?
						}
						if (isset($result['SubIFD']['DigitalZoomRatio'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("digital zoom ratio"))?></div>
							<?=trim($result['SubIFD']['DigitalZoomRatio'])?>
						</td>
						<?
						}
						if (isset($result['IFD0']['Make'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("camera brand"))?></div>
							<?=trim($result['IFD0']['Make'])?>
						</td>
						<?
						}
						if (isset($result['IFD0']['Model'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("camera model"))?></div>
							<?=trim($result['IFD0']['Model'])?>
						</td>
						<?
						}
						if (isset($result['SubIFD']['FileSource'])) {
						?>
						<td style="width: 14%;">
							<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("camera type"))?></div>
							<?=trim($result['SubIFD']['FileSource'])?>
						</td>
						<? } ?>
					</tr>
				</table>
			</div>
			<? if ($object->hasRight("write")) { ?>
				<div id="settings" style="display: none;">
					<hr/>
					<a href="javascript:void(null);" onclick="popWin=open('<?=gettpl_www("popups/regionmaker")?>?node_id=<?=$object->getNodeId()?>','PopUpWindow','width=<?=($thumbnail->width+50)?>,height=<?=($thumbnail->height+150)?>,scrollbars=1,status=0'); popWin.opener=self; popWin.focus(); popWin.moveTo(150,50); return false"><?=ucf(i18n("create region"))?></a>
					<br/>
					<?=$rotate_left.$rotate_right?>
				</div>
			<? } ?>
		</center>
	</div>
</div>

<div class="main">
	<center>
	<?
		if ($next !== false || $prev !== false)
		{
		?>
		<table>
			<tr>
				<td style="vertical-align: top; text-align: center;">
				<?
					if ($prev !== false)
					{
					?>
						<div class="show_item_wrapper">
							<?=compiletpl("scripts/show/item", array(), $prev)?>
							<div class="clear"></div>
						</div>
						<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("previous"))?></div>
					<?
					}
					else
					{
					?>
						<div class="show_item_wrapper">
							<div class="show_item">&nbsp;</div>
							<div class="clear"></div>
						</div>
						<div style="font-weight: bold; text-align: center;"><?=ucf(i18n("no more files"))?></div>
					<?
					}
				?>
				</td>
				<td style="vertical-align: top; text-align: center;">
					<div class="show_item_wrapper">
						<?=compiletpl("scripts/show/item", array("disabled"=>true), $object)?>
						<div class="clear"></div>
					</div>
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("this"))?></div>
					<?=ucf(i18n("file"))." $n ".i18n("of")." ".count($family)?>
				</td>
				<td style="vertical-align: top; text-align: center;">
				<?
					if ($next !== false)
					{
					?>
						<div class="show_item_wrapper">
							<?=compiletpl("scripts/show/item", array(), $next)?>
							<div class="clear"></div>
						</div>
						<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("next"))?></div>
					<?
					}
					else
					{
					?>
						<div class="show_item_wrapper">
							<div class="show_item">&nbsp;</div>
							<div class="clear"></div>
						</div>
						<div style="font-weight: bold; text-align: center;"><?=ucf(i18n("no more files"))?></div>
					<?
					}
				?>
				</td>
				
			</tr>
		</table>
		<? } ?>
	</center>
</div>
<?//PrintPre($result)?>