<?
require_once("class.var.php");

class mClass
{
	function GetNameList()
	{
		// Load class from DB
		$query = "SELECT name FROM `classes` ORDER BY name";
		$result = mysql_query($query) or die("mClass::GetList: " . mysql_errno() . " " . mysql_error());
			
		$list = array();
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$list[] = $row['name'];
		
		return $list;
	}
}

?>