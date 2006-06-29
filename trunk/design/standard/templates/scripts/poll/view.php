<?
$polldir_id = getNode("/root/polls");
			
if ($polldir_id > 0)
{
	$polls = fetch("FETCH node WHERE link:node_top='$polldir_id' AND link:type='sub' AND property:class_name='poll' NODESORTBY property:version SORTBY property:created");
	
	$active_polls = array();
	$now = time();
	foreach ($polls as $object)
	{
		if (strtotime($object->getVarValue("closedate")) < $now || strtotime($object->getVarValue("opendate")) > $now)
			continue;

		$active_polls[] = $object;
	}
	
	if (count($active_polls) > 0)
	{
	?>
		<div class="title">
			<a class="right" href="javascript:void(null)" onclick="toggleSidebarContainer('polls')"><?=img(imgpath("1downarrow.png"), "", "", "polls_right")?></a>
			<a class="left" href="javascript:void(null)" onclick="toggleSidebarContainer('polls')"><?=img(imgpath("1downarrow.png"), "", "", "polls_left")?></a>
			<?=ucf(i18n("polls"))?>
		</div>
		<div id="polls_container" class="container">
			<div class="polls_wrapper">
				<div class="polls_wrapper_margin">
					<?
					$polldir_id = getNode("/root/polls");
					
					if ($polldir_id <= 0)
					{
						?>
						<div class="poll">
							<div class="alternatives">
								<?=ucf(i18n("no active polls found"))?>
							</div>
						</div>
						<?
					}
					else
					{
						$polls = fetch("FETCH node WHERE link:node_top='$polldir_id' AND link:type='sub' AND property:class_name='poll' NODESORTBY property:version SORTBY property:created");
						
						$now = time();
						foreach ($polls as $object)
						{
							if (strtotime($object->getVarValue("closedate")) < $now || strtotime($object->getVarValue("opendate")) > $now)
								continue;
			
							include(gettpl("scripts/poll/poll_view", $object));
						}
					}
				?>
				</div>
			</div>
		</div>
	<?
	}
}
?>