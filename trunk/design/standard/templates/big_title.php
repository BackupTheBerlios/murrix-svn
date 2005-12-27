<div style="margin-top: 5px;">
	<table  cellspacing="0" cellpadding="0" height="39" width="100%">
		<tr>
			<td style="background-image: url(<?=imgpath("title-left.png")?>);" width="15">
			</td>
			
			<? if (!empty($left)) { ?>
				<td style="background-image: url(<?=imgpath("title-middle.png")?>); background-repeat: repeat-x;">
					<?=$left?>
				</td>
			<? } ?>

			<? if (!empty($center)) { ?>
				<td style="background-image: url(<?=imgpath("title-middle.png")?>); background-repeat: repeat-x;" align="center">
					<?=$center?>
				</td>
			<? } ?>

			<? if (!empty($right)) { ?>
				<td style="background-image: url(<?=imgpath("title-middle.png")?>); background-repeat: repeat-x;" align="right">
					<?=$right?>
				</td>
			<? } ?>
			
			<td style="background-image: url(<?=imgpath("title-right.png")?>);" width="15">
			</td>
		</tr>
	</table>
</div>
