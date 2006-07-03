<div class="main">
	<center>
	<?
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
			//$result = read_exif_data_raw($filename, 0);
			
			$angle = $object->getMeta("angle", "");
			
			$thumbnail = getThumbnail($value_id, $maxsize, 0, $angle);
			
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
				
				$data = "<div style=\"text-align: left; overflow: hidden; width: {$thumbnail->width}px; height: {$thumbnail->height}px;\">";
					
				if (count($rlist) > 0)
				{
					$data .= "<img id=\"id{$thumbnail->id}\" class=\"image-border\" style=\"width: {$thumbnail->width}px; height: {$thumbnail->height}px;\" usemap=\"#map{$thumbnail->id}\" src=\"?thumbnail={$thumbnail->id}\"/>";
					$data .= drawImageRegions($thumbnail->height, $thumbnail->width, "map".$thumbnail->id, $rlist);
				}
				else
					$data .= "<img id=\"id{$thumbnail->id}\" class=\"image-border\" style=\"width: {$thumbnail->width}px; height: {$thumbnail->height}px;\" src=\"?thumbnail={$thumbnail->id}\"/>";
					
				$data .= "</div>";
				
				if ($object->hasRight("write"))
					$data .= "<a href=\"javascript:void(null);\" onclick=\"popWin=open('".gettpl_www("popups/regionmaker")."?node_id=".$object->getNodeId()."','PopUpWindow','width=".($thumbnail->width+50).",height=".($thumbnail->height+150).",scrollbars=1,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false\">[".ucf(i18n("create region"))."]</a>";
			}
			else
				$data = img(geticon($type, 128));
				
			list($width, $height, $type2, $attr) = @getimagesize($filename);
			
			$datetime = "";
			if (isset($result['IFD0']['DateTime']))
			{
				$date = trim($result['IFD0']['DateTime']);
				list($date, $time) = explode(" ", $date);
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
			if ($prev !== false) echo cmd(img(imgpath("left.png")), "exec=show&node_id=".$prev->getNodeId());
			echo " ".cmd(img(imgpath("up.png")), "exec=show&node_id=".$parent->getNodeId())." ";
			if ($next !== false) echo cmd(img(imgpath("right.png")), "exec=show&node_id=".$next->getNodeId());
		?>
		</div>
		<?/*<a target="top" href="?file=<?=$value_id?>"><?=$data?></a>*/?>
		
		<?
		
		echo $data;
		
		$description = $object->getVarValue("description");
		if (!empty($description))
			echo "<br/>$description<br/>";
		?>
		<table style="width: <?=$maxsize?>px; text-align: center;">
			<tr>
				<? if (!empty($datetime)) { ?>
				<td style="width: 20%;">
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("picture taken"))?></div>
					<?=$datetime?>
				</td>
				<?
				}
				if (!empty($width)) {
				?>
				<td style="width: 20%;">
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("geometry"))?></div>
					<?=$width."x".$height?>
				</td>
				<?
				}
				if ($angle >= 0) {
				?>
				<td style="width: 20%;">
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("angle"))?></div>
					<?="$rotate_left$rotate_right$angle ".i18n("degrees")?>
				</td>
				<?
				}
				if (!empty($filename)) {
				?>
				<td style="width: 20%;">
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("filesize"))?></div>
					<?=DownloadSize(@filesize($filename))?>
				</td>
				<td style="width: 20%;">
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("filename"))?></div>
					<a href="?file=<?=$value_id?>&download=1"><?=img(geticon("download"))." ".$object->getVarValue("file", true)?></a>
				</td>
				<? } ?>
			</tr>
		</table>
		<table style="width: <?=$maxsize?>px; text-align: center;">
			<tr>
				<? if (isset($result['SubIFD']['MakerNote']['ImageNumber'])) { ?>
				<td style="width: 20%;">
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("image number"))?></div>
					<?=trim($result['SubIFD']['MakerNote']['ImageNumber'])?>
				</td>
				<?
				}
				if (isset($result['SubIFD']['DigitalZoomRatio'])) {
				?>
				<td style="width: 20%;">
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("digital zoom ratio"))?></div>
					<?=trim($result['SubIFD']['DigitalZoomRatio'])?>
				</td>
				<?
				}
				if (isset($result['IFD0']['Make'])) {
				?>
				<td style="width: 20%;">
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("camera brand"))?></div>
					<?=trim($result['IFD0']['Make'])?>
				</td>
				<?
				}
				if (isset($result['IFD0']['Model'])) {
				?>
				<td style="width: 20%;">
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("camera model"))?></div>
					<?=trim($result['IFD0']['Model'])?>
				</td>
				<?
				}
				if (isset($result['SubIFD']['FileSource'])) {
				?>
				<td style="width: 20%;">
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("camera type"))?></div>
					<?=trim($result['SubIFD']['FileSource'])?>
				</td>
				<? } ?>
			</tr>
		</table>
	</center>
</div>
<div class="main">
	<center>
	<?
		if ($next !== false || $prev !== false)
		{
		?>
		<table>
			<tr>
				<? if ($prev !== false) { ?>
				<td style="vertical-align: top; text-align: center;">
					<div class="show_item_wrapper">
					<?
						$child_bak = $child;
						$child = $prev;
						include(gettpl("show_item", $child));
						$child = $child_bak;
						?>
						<div class="clear"></div>
					</div>
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("previous"))?></div>
				</td>
				<? } ?>
				<td style="vertical-align: top; text-align: center;">
					<div class="show_item_wrapper">
					<?
						$child_bak = $child;
						$child = $object;
						$disabled = true;
						include(gettpl("show_item", $child));
						$disabled = false;
						$child = $child_bak;
						?>
						<div class="clear"></div>
					</div>
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("this"))?></div>
					<?=ucf(i18n("file"))." $n ".i18n("of")." ".count($family)?>
				</td>
				<? if ($next !== false) { ?>
				<td style="vertical-align: top; text-align: center;">
					<div class="show_item_wrapper">
					<?
						$child_bak = $child;
						$child = $next;
						include(gettpl("show_item", $child));
						$child = $child_bak;
						?>
						<div class="clear"></div>
					</div>
					<div style="font-weight: bold; text-align: center;"><?=ucw(i18n("next"))?></div>
				</td>
				<? } ?>
			</tr>
		</table>
		<? } ?>
	</center>
</div>