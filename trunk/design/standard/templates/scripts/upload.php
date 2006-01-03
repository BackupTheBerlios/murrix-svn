<?
echo externcmd(img(geticon("global"))." ".ucf(i18n("external link here")), $_SESSION['murrix']['lastcmd'], "externlink");

$current_view = "upload";
include(gettpl("adminpanel", $object));

$vars = $object->GetVars();

$left = img(geticon("attach"))."&nbsp;".ucf(i18n("upload"));
$right = $center = "";
include(gettpl("big_title"));

global $wwwpath;

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
		<param name="actionURL" value="<?=$wwwpath?>/upload.php?PHPSESSID=<?=session_id()?>">
		<param name="checkResponse" value="true">
		<param name="showServerResponse" value="true">
		<param name="realTimeResponse" value="true">
		<param name="overwriteContentType" value="true">
		<param name="showFilePaths" value="true">
		<param name="preventDoubles" value="true">
		<param name="labelBytes" value="Size">
		<param name="removeBorders" value="true">
		<param name="maxTotalRequestSize" value="7000000">

		<comment>
			<embed type="application/x-java-applet;version=1.4.2"
				width="100%"
				height="500"
				code="JUpload/startup.class"
				archive="<?=$wwwpath?>/3dparty/jupload/jupload.jar"
				name="JUpload"
				pluginspage="http://java.sun.com/j2se/1.4/download.html"
				progressbar="true"
				actionURL="<?=$wwwpath?>/upload.php?PHPSESSID=<?=session_id()?>"
				checkResponse="true"
				showServerResponse="true"
				realTimeResponse="true"
				overwriteContentType="true"
				showFilePaths="true"
				preventDoubles="true"
				labelBytes="Size"
				removeBorders="true"
				maxTotalRequestSize="7000000">

			<noembed>
				No Java 2 SDK, Standard Edition v 1.4 support for APPLET!
			</noembed>

			</embed>
		</comment>
	</object>
</div>

