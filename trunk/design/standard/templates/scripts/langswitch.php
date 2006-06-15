<?
if (isset($_SESSION['murrix']['languages']) && is_array($_SESSION['murrix']['languages']))
{
	foreach ($_SESSION['murrix']['languages'] as $language)
	{
		if ($language == $_SESSION['murrix']['language'])
			continue;
			
		echo cmd(img(imgpath("$language.jpg")), "exec=langswitch&language=$language");
	}
}
?>