<?

class Calendar
{
	function Calendar()
	{
	}

	// Returns an array with the dates for the week
	function getWeek($indate)
	{
		$day_of_week = date("w", strtotime($indate));
		$day_of_week = $day_of_week == 0 ? $day_of_week = 6 : $day_of_week-1;

		if ($day_of_week > 0)
			$first_day_date = date("Y-m-d", strtotime("-$day_of_week days", strtotime($indate)));
		else
			$first_day_date = $indate;
	
		$first_day_timestamp = strtotime($first_day_date);
		
		$week = array($first_day_date);
		for ($n = 1; $n < 7; $n++)
			array_push($week, date("Y-m-d", strtotime("+$n days", $first_day_timestamp)));

		return $week;
	}


}

?>
