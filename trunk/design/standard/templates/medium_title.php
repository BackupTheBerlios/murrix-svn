<?
if (!isset($flip))
	$flip = "";
?>

<div style="margin-top: 5px;">
	<table  cellspacing="0" cellpadding="0" style="height: 22px" width="100%">
		<tr>
			<td style="background-image: url(<?=imgpath("title-medium-left$flip.png")?>);" width="12">
			</td>

			<? if (!empty($left)) { ?>
				<td style="background-image: url(<?=imgpath("title-medium-middle$flip.png")?>); background-repeat: repeat-x;">
					<?=$left?>
				</td>
			<? } ?>

			<? if (!empty($center)) { ?>
				<td style="background-image: url(<?=imgpath("title-medium-middle$flip.png")?>); background-repeat: repeat-x;" align="center">
					<?=$center?>
				</td>
			<? } ?>

			<? if (!empty($right)) { ?>
				<td style="background-image: url(<?=imgpath("title-medium-middle$flip.png")?>); background-repeat: repeat-x;" align="right">
					<?=$right?>
				</td>
			<? } ?>
			
			<td style="background-image: url(<?=imgpath("title-medium-right$flip.png")?>);" width="12">
			</td>
		</tr>
	</table>
</div>