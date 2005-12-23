<?

class ReadYear
{
	function ReadYear()
	{
	}

	function GetWeekName($date = 0)
	{
		if ($date == 0)
			$date = strtotime($_GET['date']);
	
		$date_vector = getdate($date);
		
		$w = 0;
		
		$week_names[$w]['name'] = "Jullov";
		$week_names[$w]['timestamp'] = strtotime("2005-01-01");
		$w++;
		
		$week_names[$w]['name'] = "Omtentavecka";
		$week_names[$w]['timestamp'] = strtotime("2005-01-10");
		$w++;
		
		$week_names[$w]['name'] = "LV1";
		$week_names[$w]['timestamp'] = strtotime("2005-01-17");
		$w++;
		
		$week_names[$w]['name'] = "LV2";
		$week_names[$w]['timestamp'] = strtotime("2005-01-24");
		$w++;
		
		$week_names[$w]['name'] = "LV3";
		$week_names[$w]['timestamp'] = strtotime("2005-01-31");
		$w++;
		
		$week_names[$w]['name'] = "LV4";
		$week_names[$w]['timestamp'] = strtotime("2005-02-07");
		$w++;
		
		$week_names[$w]['name'] = "LV5";
		$week_names[$w]['timestamp'] = strtotime("2005-02-14");
		$w++;
		
		$week_names[$w]['name'] = "LV6";
		$week_names[$w]['timestamp'] = strtotime("2005-02-21");
		$w++;
		
		$week_names[$w]['name'] = "LV7";
		$week_names[$w]['timestamp'] = strtotime("2005-02-28");
		$w++;
		
		$week_names[$w]['name'] = "LV8";
		$week_names[$w]['timestamp'] = strtotime("2005-03-07");
		$w++;
		
		$week_names[$w]['name'] = "Tentavecka";
		$week_names[$w]['timestamp'] = strtotime("2005-03-14");
		$w++;
		
		$week_names[$w]['name'] = "Påsklov";
		$week_names[$w]['timestamp'] = strtotime("2005-03-21");
		$w++;
		
		$week_names[$w]['name'] = "Omtentavecka";
		$week_names[$w]['timestamp'] = strtotime("2005-03-28");
		$w++;

		$week_names[$w]['name'] = "LV1";
		$week_names[$w]['timestamp'] = strtotime("2005-04-04");
		$w++;
		
		$week_names[$w]['name'] = "LV2";
		$week_names[$w]['timestamp'] = strtotime("2005-04-11");
		$w++;
		
		$week_names[$w]['name'] = "LV3";
		$week_names[$w]['timestamp'] = strtotime("2005-04-18");
		$w++;
		
		$week_names[$w]['name'] = "LV4";
		$week_names[$w]['timestamp'] = strtotime("2005-04-25");
		$w++;
		
		$week_names[$w]['name'] = "LV5";
		$week_names[$w]['timestamp'] = strtotime("2005-05-02");
		$w++;
		
		$week_names[$w]['name'] = "LV6";
		$week_names[$w]['timestamp'] = strtotime("2005-05-09");
		$w++;
		
		$week_names[$w]['name'] = "LV7";
		$week_names[$w]['timestamp'] = strtotime("2005-05-16");
		$w++;
		
		$week_names[$w]['name'] = "Tentavecka";
		$week_names[$w]['timestamp'] = strtotime("2005-05-23");
		$w++;
		
		$week_names[$w]['name'] = "Sommarlov";
		$week_names[$w]['timestamp'] = strtotime("2005-05-30");
		$w++;

		for ($n = 1; $n < count($week_names); $n++)
		{
			if ($date < $week_names[$n]['timestamp'])
				return $week_names[$n-1]['name'];
		}
		
		return "";
	}
}

?>