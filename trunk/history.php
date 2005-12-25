<?
require_once("system/functions.php");

$abspath = getcwd();
$wwwpath = GetParentPath($_SERVER['REQUEST_URI']);

session_name("MURRIX21");
session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>MURRiX Command Frame</title>
		<script type="text/javascript" src="system/murrix.js"></script>
	</head>
	<body>
		<script type="text/javascript">
		<!--
			<?
			$cmd = urldecode($_GET['cmd']);
			if (!empty($cmd))
			{
				$cmd = str_replace("\\", "", $cmd);
				echo "parent.$cmd";
			}
			?>
		// -->
		</script>
	</body>
</html>