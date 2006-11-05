<?
echo compiletpl("title/big", array("left"=>"License"));

global $abspath;

echo nl2br(getFile("$abspath/docs/LICENSE.txt"));

?>
<center>
<?
	echo cmd(img(imgpath("left.png")), "exec=install&action=preinstall");
	echo cmd(img(imgpath("right.png")), "exec=install&action=database");
?>
</center>