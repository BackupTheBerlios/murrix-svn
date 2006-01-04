<div class="show_line">
	<div class="show_line_logo">
		<?
		$maxsize = 64;
		?>
		
		<div style="text-align: center; width: <?=$maxsize?>px; height: <?=$maxsize?>px;">
		<?
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
			
			echo cmd($thumbnail->Show(true), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))");
		?>
		</div>
	</div>
	<div class="show_line_logo_hidden"></div>
	<div class="show_line_main_right">
	<?
		$admin = "";

		if ($child->hasRight("edit"))
		{
			$admin .= cmd(img(geticon("edit")), "Exec('edit','zone_main', Hash('path', '".$child->getPath()."'))");
		}

		if ($child->hasRight("delete"))
		{
			$admin .= "&nbsp;";
			$admin .= cmd(img(geticon("delete")), "Exec('delete','zone_main', Hash('path', '".$child->getPath()."'))");
		}

		echo $admin;
	?>
	</div>
	<div class="show_line_main">
		<div class="show_line_main_top">
			<div class="show_line_main_top_inner">
				<span class="show_line_main_top_inner_title"><?=cmd($child->getName(), "Exec('show','zone_main', Hash('path', '".$child->getPath()."'))")?></span> -
				<?
				$emails = $child->getVarValue("emails");

				if (is_array($emails))
					echo "<a href=\"mailto:".$emails[0]."\">".$emails[0]."</a>";
				else
					echo "<a href=\"mailto:".$emails."\">".$emails."</a>";
				?>
			</div>
		</div>

		<div class="show_line_main_bottom">
			<b><?=ucf(i18n("mobilephone"))?>:</b> <?=$child->getVarValue("mobilephone")?><br/>
			<b><?=ucf(i18n("homephone"))?>:</b> <?=$child->getVarValue("homephone")?><br/>
		</div>
	</div>
	<div id="clear"></div>
</div>