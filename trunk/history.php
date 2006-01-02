<?
require_once("vars.php");
require_once("session.php");
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
			if (empty($_GET['cmd']))
			{
				$cmd = urldecode($_SESSION['murrix']['cmd']);
				unset($_SESSION['murrix']['cmd']);
			}
			else
				$cmd = urldecode($_GET['cmd']);
				
			if (!empty($cmd))
			{
				$cmd = str_replace("\\", "", $cmd);
				echo "parent.$cmd";
				$_SESSION['murrix']['lastcmd'] = $cmd;
			}
			?>
		// -->
		</script>
	</body>
</html>