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

$oFCKeditor->Config = array(	"AutoDetectLanguage" => false,
				"DefaultLanguage" => ($_SESSION['murrix']['language'] == "swe" ? "sv" : "en"),
				"SkinPath" => $oFCKeditor->BasePath."editor/skins/silver/",
				"CustomConfigurationsPath" => "$wwwpath/".getThemeConstant("fck_config_path")
				);
?>
<div style="background-color: white;">
	<?=$oFCKeditor->Create()?>
</div>