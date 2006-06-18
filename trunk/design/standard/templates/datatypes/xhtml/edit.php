<?

/*<textarea style="width: 100%; height: 500px;" class="form" id="<?=$args['varname']?>" name="<?=$args['varname']?>"><?=$args['value']?></textarea>*/

global $wwwpath, $abspath;

$oFCKeditor = new FCKeditor($args['varname']) ;
$oFCKeditor->BasePath = "$wwwpath/3dparty/FCKeditor/";

$oFCKeditor->Value = stripslashes($args['value']);

$oFCKeditor->Height = 500;
$oFCKeditor->ToolbarSet = (empty($args['extra']) ? "Default" : $args['extra']);

$conffile = "$wwwpath/design/standard/fckconfig.php";
if (file_exists("$abspath/design/".$_SESSION['murrix']['theme']."/fckconfig.php"))
	$conffile = "$wwwpath/design/".$_SESSION['murrix']['theme']."/fckconfig.php";
	
$oFCKeditor->Config = array(	"AutoDetectLanguage" => false,
				"DefaultLanguage" => ($_SESSION['murrix']['language'] == "swe" ? "sv" : "en"),
				"SkinPath" => $oFCKeditor->BasePath."editor/skins/silver/",
				"CustomConfigurationsPath" => $conffile
				);

$oFCKeditor->Create();

?>