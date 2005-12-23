<?
function guiDrawTreeMenuRecursive($object, $start = true)
{
	$subobjects = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND (property:class_name='folder' OR property:class_name='group' OR property:class_name='forum_topic') NODESORTBY !property:version SORTBY property:name");

	if (!$start)
	{
		echo "<li>";
		$child = $object;
		include(gettpl("small_line", $child));
	}
	
	$num_sub = count($subobjects);
	if ($num_sub > 0)
	{
		if (!$start)
			echo "<ul>";

		for ($i = 0; $i < $num_sub; $i++)
			guiDrawTreeMenuRecursive($subobjects[$i], false);

		if (!$start)
			echo "</ul>";
	}

	if (!$start)
		echo "</li>";
}

function guiList($list, $endstring = "% rows")
{
	?><div class="listwrapper">
		<table class="list">
			<tr><?
				foreach ($list[0] as $titelname)
				{
					?><td class="titel">
						<?=$titelname?>
					</td><?
				}
			?></tr><?
			
			if (count($list) > 1)
			{
				for ($n = 1; $n < count($list); $n++)
				{
					?><tr><?
					
					$class = "main";
					if ($n == count($list) - 1 && $n == 1)
						$class = "main_single";
					else if ($n == 1) // first;
						$class = "main_first";
					else if ($n == count($list) - 1) // last
						$class = "main_last";
					
					foreach ($list[$n] as $data)
					{
					?>
						<td class="<?=$class?>">
							<?=$data?>
						</td>
					<?
					}
					?></tr><?
				}
			}
			else
			{
				?><tr><td colspan="<?=count($list[0])?>" class="main_empty"></td></tr><?
			}
			
			?><tr>
				<td colspan="<?=count($list[0])?>" class="titel">
					<?=str_replace("%", count($list)-1, $endstring)?>
				</td>
			</tr>
		</table>
	</div><?
}

function DrawWeek($timestamp)
{
	$year = date("Y", $timestamp);
	$month = date("m", $timestamp);
	$day = date("d", $timestamp);

	$days_of_month = date("j", mktime(0, 0, 0, $month+1, 0, $year));
	$date_info = getdate($timestamp);
	$day_of_week = ($date_info['wday']-1)%7;
		
	// Hitta datum på måndagen
	$date_of_monday = $day-$day_of_week;
	
	if ($date_of_monday <= 0)
	{
		$days_of_last_month = date("j", mktime(0, 0, 0, $month, 0, $year));
		$date_of_monday = $days_of_last_month+$date_of_monday;
		
		$days_of_month = $days_of_last_month;
		$month--;
	}
	
	echo "<table cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr>";
	
	$cur_day = date("d");
	$cur_month = date("m");
	$cur_year = date("Y");
	
	for ($i = 0; $i < 7; $i++)
	{
		$num = $i+$date_of_monday;
		
		$month_here = $month;
		if ($num > $days_of_month)
		{
			$num -= $days_of_month;
			$month_here++;
		}
			
		
		$num_str = $num;
		if (strlen($num) == 1)
			$num_str = "&nbsp;$num_str";
			
		//$query = "SELECT id FROM mod_news WHERE calendar = '$year-$month_here-$num' ORDER BY id ASC";
		//$result = mysql_query($query) or die("PrintEvents2: " . mysql_errno() . " " . mysql_error());
		//if (mysql_num_rows($result) > 0)
		//	$num_str = "<b><a target='mainwindow' href='main.php4?module=news&date=$year-$month_here-$num'>$num_str</a></b>";

		echo "<td style=\"text-align: center; border-color: #000000; border-width: 1px\">";
		
		if ($i > 4)
			echo "<font color='red'>".(($num == $cur_day && $month == $cur_month && $year == $cur_year) ? "<u>$num_str</u>" : $num_str)."</font>&nbsp;";
		else
			echo (($num == $cur_day && $month == $cur_month && $year == $cur_year) ? "<u>$num_str</u>" : $num_str)."&nbsp;";
			
		echo "</td>";
	}
	
	echo "</tr>";
	echo "</table>";
}



// Calendar functions
function cal_drawDay($date, $color = "")
{
	if (!empty($color))
		$color = "color: red;";

	$border = "";
	if (date("Y-m-d") == $date)
		$border = "border-width: 2px; border-color: red; border-style: solid; font-weight: bold;";
	?>
	<td>
		<div class="main_bg">
			<div class="main" style="text-align: right; height: 100px; <?=$color?> <?=$border?>">
				<?
				$day = date("j", strtotime($date));
				if ($day == 1) echo ucf(i18n(strtolower(date("F", strtotime($date)))))." $day";
				else echo $day;
				?>
				<hr style="border: 0; color: #FCE464; background-color: #FCE464; height: 1px; margin: 0;"/>
				<div style="font-weight: normal; font-size: 9px; text-align: left; overflow: auto;">
					<nobr>
					<?
					$node_id = resolvePath("/Root/Etek/Hidden/Events", "eng");

					$day = date("j", strtotime($date));
					$month = date("n", strtotime($date));
					$year = date("Y", strtotime($date));
				
					$children = fetch("FETCH node WHERE link:node_top='$node_id' AND link:type='sub' AND property:class_name='event' AND var:day='$day' AND (var:month='$month' OR var:month='-1') AND (var:year='$year' OR var:year='-1') NODESORTBY !property:version SORTBY property:name");

					
					foreach ($children as $child)
					{
						echo cmd($child->getName(), "Exec('show', 'zone_main', Hash('path', '".$child->getPath()."'))", "", $child->getName());
						echo "<br/>";
					}
					?>
					</nobr>
				</div>
			</div>
		</div>
	</td>
<?
}


function cal_drawWeek($week)
{
?>
	<tr>
		<td style="text-align: center; padding-right: 5px; height: 100px; font-size: 25px; font-weight: bold; vertical-align: middle; width: 0%;">
			<?=date("W", strtotime($week[0]))?>
			<span style="font-size: 11px;"><?=date("Y", strtotime($week[0]))?></span>
		</td>
		<?
		for ($n = 0; $n < count($week); $n++)
			cal_drawDay($week[$n], $n > 4 ? "red" : "");
		?>
	</tr>
<?
}

function cal_drawHeadWeek($week)
{
	for ($n = 0; $n < count($week); $n++)
	{
	?>
		<td style="text-align: center;  <?=($week[$n] == date("Y-m-d") ? "text-decoration: underline;" : "")?>">
			<?=cmd(date("j", strtotime($week[$n])), "Exec('calendar','zone_main', Hash('date', '".$week[$n]."'))", ($n > 4 ? "red" : ""))?>
		</td>
	<?
	}
}

?>