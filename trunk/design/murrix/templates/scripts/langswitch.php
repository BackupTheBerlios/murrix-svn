<?
if (isset($_SESSION['murrix']['languages']) && is_array($_SESSION['murrix']['languages']))
{
	foreach ($_SESSION['murrix']['languages'] as $language)
	{
		if ($language == $_SESSION['murrix']['language'])
			continue;
		?>
		
		<form name="sLangswitch" id="sLangswitch" action="javascript:void(null);" onsubmit="Post('langswitch','sLangswitch');">
			<div>
				<input class="hidden" type="hidden" name="language" value="<?=$language?>"/>
				<input class="image" type="image" src="<?=imgpath($language.".jpg")?>" alt="<?=ucf(i18n($language))?>"/><br/>
				<?=ucf(i18n($language, $language))?>
			</div>
		</form>
		<?
	}
}
?>