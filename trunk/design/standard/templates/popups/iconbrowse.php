<?

list($abspath) = explode("/design/", getcwd());

require_once("$abspath/system/functions.php");
require_once("$abspath/system/design.php");
require_once("$abspath/session.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>MURRiX <?=ucf(i18n("icon"))?> <?=ucf(i18n("browse"))?></title>
		<meta name="robots" content="noindex"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
		
		<link rel="shortcut icon" href="<?=geticon("murrix")?>" type="image/x-icon"/>
		<?
		$js = getcss();
		for ($i = 0; $i < count($js); $i++)
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$js[$i]."\"/>\n";

		?>
	</head>
	
	<body>
		<div id="main">
			<div id="content">
			<?
				$left = $right = "";
				$center = ucf(i18n("icon"))." ".ucf(i18n("browse"));
				include(gettpl("big_title"));
				?>
				<div class="main">
				<?
					global $abspath, $wwwpath;
	
					$files = GetSubfiles("$abspath/design/standard/icons/64");
	
					if (count($files) > 0)
					{
						echo ucf(i18n("standard"))." ".i18n("icons");
						echo "<hr/>";
						foreach ($files as $file)
						{
							list($width, $height, $type, $attr) = getimagesize("$abspath/design/standard/icons/64/$file");
							$file = basename($file);
							echo "<div style=\"float: left; margin: 5px;\" >\n";
							echo "<a href=\"javascript:void(null);\" onclick=\"opener.document.getElementById('".$_GET['form_id']."').".$_GET['input_id'].".value='".basename($file, ".png")."'; opener.document.getElementById('".$_GET['input_id']."_img').src='".geticon(basename($file, ".png"))."';self.close();\">\n";
							echo "<img src=\"$wwwpath/design/standard/icons/64/$file\"  style=\"width: ".$width."px; height: ".$height."px;\"/>\n";
							echo "</a>\n</div>\n";
						}
						echo "<div class=\"clear\"></div>";
					}
	
					if ($_SESSION['murrix']['site'] != "standard")
					{
						$files = GetSubfiles("$abspath/design/".$_SESSION['murrix']['site']."/icons/64");
	
						if (count($files) > 0)
						{
							echo ucf(i18n("standard"))." ".i18n("icons");
							echo "<hr/>";
							foreach ($files as $file)
							{
								list($width, $height, $type, $attr) = getimagesize("$abspath/design/standard/icons/64/$file");
								$file = basename($file);
								echo "<div style=\"float: left; margin: 5px;\" >\n";
								echo "<a href=\"javascript:void(null);\" onclick=\"opener.document.getElementById('".$_GET['form_id']."').".$_GET['input_id'].".value='".basename($file, ".png")."'; opener.document.getElementById('".$_GET['input_id']."_img').src='".geticon(basename($file, ".png"))."';self.close();\">\n";
								echo "<img src=\"$wwwpath/design/standard/icons/64/$file\"  style=\"width: ".$width."px; height: ".$height."px;\"/>\n";
								echo "</a>\n</div>\n";
							}
							echo "<div class=\"clear\"></div>";
						}
					}
				?>
				</div>
			</div>
		</div>
	</body>
</html>