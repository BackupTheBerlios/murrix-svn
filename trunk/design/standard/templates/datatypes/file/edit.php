<iframe style="border: none; width: 350px; height: 23px; float: right; text-align: right;" src="<?=gettpl_www("popups/fileupload")?>?varid=<?=$args['varname']?>"></iframe>
<input disabled class="input" id="n<?=$args['varname']?>" name="n<?=$args['varname']?>" type="text" value="<?=$args['value']?>">
<input class="hidden" id="<?=$args['varname']?>" name="<?=$args['varname']?>" type="hidden" value="<?=$args['value']?>:<?=$args['filepath']?>"/>