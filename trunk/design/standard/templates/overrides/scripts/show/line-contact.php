<div class="show_line">
	<div class="show_line_logo">
		<?
		$maxsize = 64;
		?>
		<div style="text-align: center; width: <?=$maxsize?>px; height: <?=$maxsize?>px;">
		<?
			$thumb_id = $object->getVarValue("thumbnail");

			if (!empty($thumb_id))
			{
				$thumbnail = new mThumbnail($object->getVarValue("thumbnail"));
	
				if ($thumbnail->height > $thumbnail->width && $maxsize > 0)// höjden = maxsize;
				{
					$h = $maxsize;
					$w = $thumbnail->width * ($maxsize / $thumbnail->height);
				}
				else//bredden = maxsize
				{
					$h = $thumbnail->height * ($maxsize / $thumbnail->width);
					$w = $maxsize;
				}
	
				$thumbnail->height = $h;
				$thumbnail->width = $w;

				$img = $thumbnail->Show(true);
			}
			else
				$img = img(geticon($object->getIcon(), 64));
				
			cmd($img, "exec=show&node_id=".$object->getNodeId());
		?>
		</div>
	</div>
	<div class="show_line_main_right"></div>
	<div class="show_line_main">
		<div class="show_line_main_top">
			<div class="show_line_main_top_inner">
				<span class="show_line_main_top_inner_title">
					<?=cmd($object->getName(), "exec=show&node_id=".$object->getNodeId())?>
				</span>
				<?
				$emails = $object->getVarValue("emails");
				if (!empty($emails))
				{
					if (is_array($emails))
						echo "- <a href=\"mailto:".$emails[0]."\">".$emails[0]."</a>";
					else
						echo "- <a href=\"mailto:".$emails."\">".$emails."</a>";
				}
			?>
			</div>
		</div>

		<div class="show_line_main_bottom">
		<?
			$mobilephones = $object->getVarValue("mobilephones");
			if (!empty($mobilephones))
			{
				echo "<b>".ucf(i18n("mobilephone")).":</b> ";
				if (is_array($mobilephones))
					echo $mobilephones[0]."<br/>";
				else
					echo $mobilephones."<br/>";
			}
			
			$homephones = $object->getVarValue("homephones");
			if (!empty($mobilephones))
			{
				echo "<b>".ucf(i18n("homephone")).":</b> ";
				if (is_array($homephones))
					echo $homephones[0]."<br/>";
				else
					echo $homephones."<br/>";
			}
		?>
		</div>
	</div>
</div>
<div class="clear"></div>