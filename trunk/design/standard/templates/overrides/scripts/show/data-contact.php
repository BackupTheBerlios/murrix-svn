<table class="contact_table" cellspacing="0">
	<tr>
		<td class="left">
			<div class="main">
				<div class="container">
					<table width="100%" cellspacing="0">
						<tr>
							<td style="vertical-align: top;">
								<table class="contact_table_inner">
									<?
									$data = $object->getVarValue("fullname");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("fullname"))?>
										</td>
										<td>
										</td>
										<td class="right">
											<?=$object->getVarShow("fullname")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("gender");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("gender"))?>
										</td>
										<td>
										</td>
										<td class="right">
											<?=ucf(i18n($object->getVarShow("gender")))?>
										</td>
									</tr>
									<?
									}
									// Check event etc!!
									
									$links = $object->getLinks(0, "birth");
									
									if (count($links) > 0)
									{
										$birth_event = new mObject($links[0]['remote_id']);
										$date = $birth_event->getVarValue("date");
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("birthday"))?>
										</td>
										<td>
											<?=img(geticon("birthday"))?>
										</td>
										<td class="right">
											<?=$date?><br/>
											<?=getAge($date)?> <?=i18n("years old")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("mobilephones");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("mobilephones"))?>
										</td>
										<td>
											<?=img(geticon("phone"))?>
										</td>
										<td class="right">
											<?=$object->getVarShow("mobilephones")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("homephones");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("homephones"))?>
										</td>
										<td>
											<?=img(geticon("phone"))?>
										</td>
										<td class="right">
											<?=$object->getVarShow("homephones")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("workphones");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("workphones"))?>
										</td>
										<td>
											<?=img(geticon("phone"))?>
										</td>
										<td class="right">
											<?=$object->getVarShow("workphones")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("emails");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("emails"))?>
										</td>
										<td>
											<?=img(geticon("mail"))?>
										</td>
										<td class="right">
											<?=$object->getVarShow("emails")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("address");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("address"))?>
										</td>
										<td>
											<?=img(geticon("mailaccount"))?>
										</td>
										<td class="right">
											<?=$object->getVarShow("address")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("icq");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("icq"))?>
										</td>
										<td>
											<?=img(geticon("icq"))?>
										</td>
										<td class="right">
											<?=$object->getVarShow("icq")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("msn");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("msn"))?>
										</td>
										<td>
											<?=img(geticon("msn"))?>
										</td>
										<td class="right">
											<?=$object->getVarShow("msn")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("skype");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("skype"))?>
										</td>
										<td>
											<?=img(geticon("skype"))?>
										</td>
										<td class="right">
											<?=$object->getVarShow("skype")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("allergies");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("allergies"))?>
										</td>
										<td>
										</td>
										<td class="right">
											<?=$object->getVarShow("allergies")?>
										</td>
									</tr>
									<?
									}
									$data = $object->getVarValue("other");
									if (!empty($data)) {
									?>
									<tr>
										<td class="left">
											<?=ucf(i18n("other"))?>
										</td>
										<td>
										</td>
										<td class="right">
											<?=$object->getVarShow("other")?>
										</td>
									</tr>
									<? } ?>
								</table>
							</td>
							<td style="text-align: right; vertical-align: top;">
								<?=$object->getVarShow("thumbnail")?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</td>
		<?
		$events = fetch("FETCH node WHERE property:class_name='event' AND link:node_top='".$object->getNodeId()."' AND link:type='sub' NODESORTBY property:version SORTBY var:date");
		
		$parents = fetch("FETCH node WHERE property:class_name='contact' AND link:node_bottom='".$object->getNodeId()."' AND link:type='parent' NODESORTBY property:version SORTBY property:name");
		
		$children = fetch("FETCH node WHERE property:class_name='contact' AND link:node_top='".$object->getNodeId()."' AND link:type='parent' NODESORTBY property:version SORTBY property:name");
		
		$mother = false;
		$father = false;
		
		foreach ($parents as $parent)
		{
			if ($parent->getVarValue("gender") == "female")
				$mother = $parent;
			else
				$father = $parent;
		}
		
		$tree = count($children) > 0 || $mother || $father;
		
		if ($tree || count($events) > 0)
		{
		?>
			<td class="right">
			<?
				if ($tree)
				{
					echo compiletpl("title/medium", array("center"=>ucf(i18n("family tree"))));
					?>
					<div class="container">
						<table width="100%" cellspacing="0">
							<tr>
								<td>
									<fieldset style="text-align: center; margin-right: 2px;">
										<legend>
											<?=ucf(i18n("mother"))?>
										</legend>
										<?
										if ($mother === false)
											echo ucf(i18n("unknown"));
										else
										{
											if ($mother->hasRight("read"))
												echo cmd($mother->getName(), "exec=show&node_id=".$mother->getNodeId());
											else
												echo $mother->getName();
										}
										?>
									</fieldset>
								</td>
								<td>
									<fieldset style="text-align: center; margin-left: 2px;">
										<legend>
											<?=ucf(i18n("father"))?>
										</legend>
										<?
										if ($father === false)
											echo ucf(i18n("unknown"));
										else
										{
											if ($father->hasRight("read"))
												echo cmd($father->getName(), "exec=show&node_id=".$father->getNodeId());
											else
												echo $father->getName();
										}
										?>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: center" align="center">
									<div class="family_tree_person">
										<?=$object->getName()?>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: right">
									<fieldset style="text-align: center">
										<legend>
											<?=ucf(i18n("children"))?>
										</legend>
										<?
										if (count($children) == 0)
											echo ucf(i18n("none"));
										else
										{
											foreach ($children as $child)
											{
												if ($child->hasRight("read"))
													echo cmd($child->getName(), "exec=show&node_id=".$child->getNodeId())."<br/>";
												else
													echo $child->getName();
											}
										}
										?>
									</fieldset>
								</td>
							</tr>
						</table>
					</div>
				<?
				}
				
				if (count($events) > 0)
				{
					echo compiletpl("title/medium", array("center"=>ucf(i18n("timeline"))));
					?>
					<div class="timeline_wrapper">
					<?
						foreach ($events as $event)
						{
							$datetime = $event->getVarValue("date");
							$time = $event->getVarValue("time");
							if (!empty($time))
								$datetime .= " $time";
								
							$description = $event->getVarShow("description");
							?>
							<div class="event">
								<div class="title">
									<?=cmd(img(geticon($event->getIcon()))." ".$event->getName(), "exec=show&node_id=".$event->getNodeId())?>
								</div>
								<div class="body" id="event_less_<?=$event->getNodeId()?>">
									<div class="date">
									<?
										if (!empty($description))
										{
										?>
											<div class="right">
												<a href="javascript:noneDisplay('event_less_<?=$event->getNodeId()?>');blockDisplay('event_more_<?=$event->getNodeId()?>')"><?=img(imgpath("1uparrow.png"))?></a>
											</div>
											<?
										}
										?>
										<?=$datetime?>
										<div class="clear"></div>
									</div>
								</div>
								<?
								if (!empty($description))
								{
								?>
									<div class="body" id="event_more_<?=$event->getNodeId()?>" style="display: none;">
										<div class="date">
											<div class="right">
												<a href="javascript:noneDisplay('event_more_<?=$event->getNodeId()?>');blockDisplay('event_less_<?=$event->getNodeId()?>')"><?=img(imgpath("1downarrow.png"))?></a>
											</div>
											<?=$datetime?>
											<div class="clear"></div>
										</div>
										<hr/>
										<?=$description?>
									</div>
								<?
								}
							?>
							</div>
						<?
						}
					?>
					</div>
				<?
				}
			?>
			</td>
		<?
		}
	?>
	</tr>
</table>