<? if ($num_pages > 1) { ?>
	<div class="main">
		<div class="container">
		<?
			if ($page_num-1 <= 0)
				echo img(imgpath("leftarrow.png"))." ";
			else
				echo cmd(img(imgpath("leftarrow.png"))." ", "exec=show&".$pagername."_page=".($page_num-1));

			for ($i = 1; $i <= $num_pages; $i++)
			{
				if ($i == $page_num)
					echo "<b>$i</b> ";
				else
					echo cmd("$i ", "exec=show&".$pagername."_page=$i");
			}

			if ($page_num+1 > $num_pages)
				echo img(imgpath("rightarrow.png"));
			else
				echo cmd(img(imgpath("rightarrow.png")), "exec=show&".$pagername."_page=".($page_num+1));
		?>
		</div>
	</div>
<? } ?>