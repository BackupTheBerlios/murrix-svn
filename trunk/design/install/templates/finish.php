<?
echo compiletpl("title/big", array("left"=>"Finish"));

echo $args['logtext'];

?>
<center>
<?
	echo cmd(img(imgpath("left.png")), "exec=install&action=config");
	echo "<a href=\"./\">".img(imgpath("right.png"))."</a>";
?>
</center>