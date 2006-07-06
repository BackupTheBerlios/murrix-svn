<div class="big_title">
	<? if (!empty($args['left'])) { ?>
		<div class="left">
			<div class="inner">
				<?=$args['left']?>
			</div>
		</div>
	<? } ?>

	<? if (!empty($args['center'])) { ?>
		<div class="center">
			<div class="inner">
				<?=$args['center']?>
			</div>
		</div>
	<? } ?>

	<? if (!empty($args['right'])) { ?>
		<div class="right">
			<div class="inner">
				<?=$args['right']?>
			</div>
		</div>
	<? } ?>
	<div class="clear"></div>
</div>