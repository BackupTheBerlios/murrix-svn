<?
echo compiletpl("title/big", array("left"=>"License"));

global $abspath;
?>
<div class="text_height">
	<?=nl2br(getFile("$abspath/docs/LICENSE.txt"))?>
</div>
<center>
<?
	echo cmd(img(imgpath("left.png")), "exec=install&action=preinstall");
	echo cmd(img(imgpath("right.png")), "exec=install&action=database");
?>
</center>