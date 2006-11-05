<?=compiletpl("title/big", array("left"=>"Database"))?>
You have to specify how MURRiX will access <b>MySQL</b>.<br/>
<br/>
<form name="sInstall" id="sInstall" action="javascript:void(null);" onsubmit="Post('install','sInstall')">
	<input class="hidden" type="hidden" name="action" value="databasecheck">
	Server adress<br/>
	<input class="textline" name="adress" value="<?=$args['adress']?>" type="text"><br/>
	<br/>
	Database name<br/>
	<input class="textline" name="name" value="<?=$args['name']?>" type="text"><br/>
	Table prefix<br/>
	<input class="textline" name="prefix" value="<?=$args['prefix']?>" type="text"><br/>
	<br/>
	Username<br/>
	<input class="textline" name="username" value="<?=$args['username']?>" type="text"><br/>
	Password<br/>
	<input class="textline" name="password" value="<?=$args['password']?>" type="password"><br/>
</form>

<center>
<?
	echo cmd(img(imgpath("left.png")), "exec=install&action=license");
	echo runjs(img(imgpath("right.png")), "Post('install','sInstall')");
?>
</center>