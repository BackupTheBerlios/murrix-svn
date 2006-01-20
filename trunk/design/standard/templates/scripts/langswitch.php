<?
if (isset($_SESSION['murrix']['languages']) && is_array($_SESSION['murrix']['languages']))
{
	foreach ($_SESSION['murrix']['languages'] as $language)
	{
		if ($language == $_SESSION['murrix']['language'])
			continue;
			
		?><a onclick="Exec('langswitch','zone_language', Hash('language', '<?=$language?>'));" href="javascript:void(null);"><?
		echo img(imgpath("$language.jpg"));
		?></a><?
	}
}