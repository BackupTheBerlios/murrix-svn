<?
header("Content-Type: text/plain");

$abspath = "../../";
$wwwpath = "";

require_once("$abspath/session.php");
require_once("$abspath/system/design.php");
require_once("$abspath/system/functions.php");

$abspath = getcwd()."/$abspath";

$smiley_path_www = "$wwwpath/".getThemeConstant("smileys_path")."/";
$smiley_path_abs = $abspath.getThemeConstant("smileys_path")."/";

//FCKConfig.StylesXmlPath = '../fckstyles.xml' ;
?>

FCKConfig.EditorAreaCSS = '<?="$wwwpath/".getThemeConstant("fck_css_path")?>';

FCKConfig.ToolbarSets["Default"] = [
	['Source','Preview','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['Link','Unlink'],
	['Image','Table','Rule','Smiley','SpecialChar','UniversalKey'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	'/',
	['Style','FontFormat','FontName','FontSize'],
	['TextColor','BGColor']
];

FCKConfig.ToolbarSets["Basic"] = [
	['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','-','About']
];

FCKConfig.ToolbarSets["Simple"] = [
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript','-','Smiley','SpecialChar','UniversalKey','OrderedList','UnorderedList','-','Link','Unlink']
];



FCKConfig.SmileyPath		= '<?=$smiley_path_www?>';
FCKConfig.SmileyColumns 	= 7;
FCKConfig.SmileyWindowWidth	= 660;
FCKConfig.SmileyWindowHeight	= 500;
FCKConfig.SmileyImages		= [
<?
$files = GetSubfiles($smiley_path_abs);
for ($n = 0; $n < count($files); $n++)
{
	echo "'".$files[$n]."'";
	if ($n != count($files)-1)
		echo ",";
}
?>
];
