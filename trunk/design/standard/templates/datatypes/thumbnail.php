<input disabled class="form" id="n<?=$args['varname']?>" name="n<?=$args['varname']?>" type="text" value="<?=$args['value']?>"/>
<a href="javascript:void(null);" onclick="popWin=open('single_upload.php?varid=<?=$args['varname']?>','PopUpWindow','width=250,height=80,scrollbars=0,status=0'); popWin.opener = self; popWin.focus(); popWin.moveTo(150,50); return false"><?=ucf(i18n("upload thumbnail"))?></a>
<input class="hidden" id="<?=$args['varname']?>" name="<?=$args['varname']?>" type="hidden" value="<?=$args['value']?>"/>