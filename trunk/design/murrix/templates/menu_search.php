<div class="menu_title">
	<?=cmd(ucf(i18n("search")), "exec=search")?>
</div>
<div class="menu_search">
	<form id="smallSearch" action="javascript:void(null);" onsubmit="Post('search','smallSearch')">
		<div>
			<input class="input" id="query" name="query" type="text" onfocus="if(this.value=='<?=ucf(i18n("enter search here"))?>!')this.value=''" onblur="if(this.value=='')this.value='<?=ucf(i18n("enter search here"))?>!'" value="<?=ucf(i18n("enter search here"))?>!"/>
			<input class="submit" type="submit" value="<?=ucf(i18n("search"))?>"/>
		</div>
	</form>
</div>