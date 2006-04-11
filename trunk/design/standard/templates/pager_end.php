<?
//if ($object->hasRight("edit") || $num_pages > 1)
if ($num_pages > 1)
{
?>
	<div class="main">
	<?
		/*$num_per_page_form = "";
		if ($object->hasRight("edit"))
		{
			$num_per_page_form = " <form style=\"display: inline;\" name=\"".$pagername."sNumPerPageSelect\" id=\"".$pagername."sNumPerPageSelect\" action=\"javascript:void(null);\" onsubmit=\"javascript:void(null)\">";
			$num_per_page_form .= "<input type=\"hidden\" class=\"hidden\" name=\"meta\" value=\"".$pagername."_num_per_page\">";
			$num_per_page_form .= "<select class=\"form\" onchange=\"Post('show','zone_main', '".$pagername."sNumPerPageSelect');\" name=\"value\">";

			$list = array(5 => 5, 10 => 10, 25 => "", 50 => 50, 100 => 100, "all" => "all");

			foreach ($list as $key => $item)
			{
				$selected = "";
				if ($key == $num_per_page_db)
					$selected = "selected";

				$num_per_page_form .= "<option $selected value=\"$item\">".(is_int($key) ? $key : ucf(i18n($key)))."</option>";
			}

			$num_per_page_form .= "</select>";
			$num_per_page_form .= "</form>";

			echo ucf(i18n("items per page"))." $num_per_page_form ";
		}*/

		//if ($num_pages > 1)
		{
			if ($page_num-1 <= 0)
				echo img(imgpath("leftarrow.png"))." ";
			else
				echo cmd(img(imgpath("leftarrow.png"))." ", "Exec('show', 'zone_main', Hash('".$pagername."_page', '".($page_num-1)."'))");

			for ($i = 1; $i <= $num_pages; $i++)
			{
				if ($i == $page_num)
					echo "<b>$i</b> ";
				else
					echo cmd("$i ", "Exec('show', 'zone_main', Hash('".$pagername."_page', '$i'))");
			}

			if ($page_num+1 > $num_pages)
				echo img(imgpath("rightarrow.png"));
			else
				echo cmd(img(imgpath("rightarrow.png")), "Exec('show', 'zone_main', Hash('".$pagername."_page', '".($page_num+1)."'))");
		}
	?>
	</div>
<?
}
?>