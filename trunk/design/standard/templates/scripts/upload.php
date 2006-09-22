<?
global $wwwpath, $abspath;

echo compiletpl("scripts/show/tabs", array("view"=>"upload"), $object);
echo compiletpl("title/big", array("left"=>img(geticon($object->getIcon()))."&nbsp;".$object->getName()), $object);

echo compiletpl("title/medium", array("left"=>ucf(i18n("upload folder"))), $object);

$subitems = GetSubfilesAndSubfolders("$abspath/upload");
if (count($subitems) > 0)
{
	$itemlist = array();
	$itemlist[] = array(ucf(i18n("name")));
	
	foreach ($subitems as $subitem)
		$itemlist[] = array($subitem);
	
	echo compiletpl("table", array("list"=>$itemlist));
//	table($itemlist, "% ".i18n("rows"));
	
	?>
	<div class="main">
		<div class="container">
			<?=cmd(ucf(i18n("upload all")), "exec=upload&action=upload&node_id=".$object->getNodeId())?>
			<div id="zone_upload_logg"></div>
		</div>
	</div>
<?
}
else
{
?>
	<div class="main">
		<div class="container">
			<?=ucf(i18n("upload folder is empty"))?>
		</div>
	</div>
<?
}

echo compiletpl("title/medium", array("left"=>ucf(i18n("upload javaapplet"))), $object);
?>
<div class="main">
	<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"
		codetype="application/java-vm"
		width="90%"
		height="500"
		name="JUpload"
		codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4-windows-i586.cab#Version=1,4,0,0">
		<param name="archive" value="<?=$wwwpath?>/3dparty/jupload/jupload.jar">
		<param name="code" value="JUpload/startup.class">
		<param name="progressbar" value="true">
		<param name="boxmessage" value="Loading JUpload Applet ...">
		<param name="actionURL" value="<?=$wwwpath?>/backends/upload.php?PHPSESSID=<?=session_id()?>&node_id=<?=$object->getNodeId()?>">
		<param name="checkResponse" value="true">
		<param name="showServerResponse" value="true">
		<param name="realTimeResponse" value="true">
		<param name="overwriteContentType" value="true">
		<param name="showFilePaths" value="true">
		<param name="preventDoubles" value="true">
		<param name="labelBytes" value="Size">
		<param name="removeBorders" value="true">
		<param name="maxTotalRequestSize" value="10000000">

		<comment>
			<embed type="application/x-java-applet;version=1.4.2"
				width="90%"
				height="500"
				code="JUpload/startup.class"
				archive="<?=$wwwpath?>/3dparty/jupload/jupload.jar"
				name="JUpload"
				pluginspage="http://java.sun.com/j2se/1.4/download.html"
				progressbar="true"
				actionURL="<?=$wwwpath?>/backends/upload.php?PHPSESSID=<?=session_id()?>&node_id=<?=$object->getNodeId()?>"
				checkResponse="true"
				showServerResponse="true"
				realTimeResponse="true"
				overwriteContentType="true"
				showFilePaths="true"
				preventDoubles="true"
				labelBytes="Size"
				removeBorders="true"
				maxTotalRequestSize="10000000">

				<noembed>
					No Java 2 SDK, Standard Edition v 1.4 support for APPLET!
				</noembed>
			</embed>
		</comment>
	</object>
</div>
