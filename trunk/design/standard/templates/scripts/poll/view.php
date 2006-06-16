<div class="polls_wrapper">
	<div class="polls_wrapper_margin">
	
		<div class="title">
			<?=ucf(i18n("polls"))?>
		</div>
		
		<?
		$polldir_id = getNode("/root/polls");
		
		if ($polldir_id <= 0)
		{
			?>
			<div class="poll">
				<?=ucf(i18n("no active polls found"))?>
			</div>
			<?
		}
		else
		{
			$polls = fetch("FETCH node WHERE link:node_top='$polldir_id' AND link:type='sub' AND property:class_name='poll' NODESORTBY property:version SORTBY property:created");
			
			foreach ($polls as $object)
			{
				include(gettpl("scripts/poll/poll_view", $object));
			}
		}
	?>
	</div>
</div>