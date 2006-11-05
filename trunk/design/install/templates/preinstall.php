<?

echo compiletpl("title/big", array("left"=>"Pre-installation checks"));

$list = array();
$list[] = array("Test", "Status");

$fatal = false;
foreach ($args['checks'] as $check)
{
	if ($check['fatal'] && !$check['status'])
		$fatal = true;
		
	$list[] = array($check['text'], $check['status'] ? "Yes" : "No".($check['fatal'] ? " - fatal error" : ""));
}

echo compiletpl("table", array("list"=>$list, "endstring"=>"&nbsp;"));
?>
<center>
<?
	echo img(imgpath("gray_left.png"));
	
	if ($fatal)
		echo img(imgpath("gray_right.png"));
	else
		echo cmd(img(imgpath("right.png")), "exec=install&action=license");
?>
</center>