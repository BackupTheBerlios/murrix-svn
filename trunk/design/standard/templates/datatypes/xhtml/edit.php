<?

/*<textarea style="width: 100%; height: 500px;" class="form" id="<?=$args['varname']?>" name="<?=$args['varname']?>"><?=$args['value']?></textarea>*/

global $wwwpath, $abspath;

$oFCKeditor = new FCKeditor($args['varname']) ;
$oFCKeditor->BasePath = "$wwwpath/3dparty/FCKeditor/";

$oFCKeditor->Value = stripslashes($args['value']);

list($toolbar, $height) = explode(":", $args['extra']);

if (empty($height))
	$oFCKeditor->Height = 500;
else
	$oFCKeditor->Height = $height;
	
if (empty($toolbar))
	$oFCKeditor->ToolbarSet = "Default";
else
	$oFCKeditor->ToolbarSet = $toolbar;

$conffile = "$wwwpath/design/standard/fckconfig.php";
if (file_exists("$abspath/design/".$_SESSION['murrix']['theme']."/fckconfig.php"))
	$conffile = "$wwwpath/design/".$_SESSION['murrix']['theme']."/fckconfig.php";
	
$oFCKeditor->Config = array(	"AutoDetectLanguage" => false,
				"DefaultLanguage" => ($_SESSION['murrix']['language'] == "swe" ? "sv" : "en"),
				"SkinPath" => $oFCKeditor->BasePath."editor/skins/silver/",
				"CustomConfigurationsPath" => $conffile
				);
?>
<div style="background-color: white;">
	<?=$oFCKeditor->Create()?>
</div>