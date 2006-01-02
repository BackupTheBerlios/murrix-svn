<?
require_once("vars.php");
require_once("session.php");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");
session_cache_limiter("public, no-store");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>MURRiX Command Frame</title>
		<meta http-equiv="Pragma" content="no-cache"/>
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