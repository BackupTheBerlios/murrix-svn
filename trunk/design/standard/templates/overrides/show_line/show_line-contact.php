<div class="show_line">
	<div class="show_line_logo">
		<?
		$maxsize = 64;
		?>
		<div style="text-align: center; width: <?=$maxsize?>px; height: <?=$maxsize?>px;">
		<?
			$thumb_id = $child->getVarValue("thumbnail");

			if (!empty($thumb_id))
			{
				$thumbnail = new mThumbnail($child->getVarValue("thumbnail"));
	
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
				$img = img(geticon($child->getIcon(), 64));
				
			$read_right = true; //$child->hasRight("read");
			if ($read_right)
				echo cmd($img, "exec=show&node_id=".$child->getNodeId());
			else
				echo $img;
		?>
		</div>
	</div>
	<div class="show_line_logo_hidden"></div>
	<div class="show_line_main_right">
	<?
		/*$admin = "";

		if ($child->hasRight("edit"))
		{
			$admin .= cmd(img(geticon("edit")), "Exec('edit','zone_main',Hash('node_id','".$child->getNodeId()."'))");
		}

		if ($child->hasRight("delete"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("delete")), "Exec('delete','zone_main',Hash('node_id','".$child->getNodeId()."'))");
		}

		echo $admin;*/
	?>
	</div>
	<div class="show_line_main">
		<div class="show_line_main_top">
			<div class="show_line_main_top_inner">
				<span class="show_line_main_top_inner_title">
				<?
					if ($read_right)
						echo cmd($child->getName(), "exec=show&node_id=".$child->getNodeId());
					else
						echo $child->getName();
				?>
				</span>
				<?
				if ($read_right)
				{
					$emails = $child->getVarValue("emails");
					if (!empty($emails))
					{
						if (is_array($emails))
							echo "- <a href=\"mailto:".$emails[0]."\">".$emails[0]."</a>";
						else
							echo "- <a href=\"mailto:".$emails."\">".$emails."</a>";
					}
				}
				?>
			</div>
		</div>

		<div class="show_line_main_bottom">
		<?
			if ($read_right)
			{
				$mobilephones = $child->getVarValue("mobilephones");
				if (!empty($mobilephones))
				{
					echo "<b>".ucf(i18n("mobilephone")).":</b> ";
					if (is_array($mobilephones))
						echo $mobilephones[0]."<br/>";
					else
						echo $mobilephones."<br/>";
				}
				
				$homephones = $child->getVarValue("homephones");
				if (!empty($mobilephones))
				{
					echo "<b>".ucf(i18n("homephone")).":</b> ";
					if (is_array($homephones))
						echo $homephones[0]."<br/>";
					else
						echo $homephones."<br/>";
				}
			}
		?>
		</div>
	</div>
</div>
<div id="clear"></div>