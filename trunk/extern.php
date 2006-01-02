<?
require_once("vars.php");
require_once("session.php");

$_SESSION['murrix']['cmd'] = $_GET['cmd'];

header("Location: $wwwpath/");
exit;
?>