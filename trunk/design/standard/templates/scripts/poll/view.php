<div class="polls_wrapper">
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
		
		foreach ($polls as $poll)
		{
			$now = time();
			if (strtotime($poll->getVarValue("closedate")) < $now || strtotime($poll->getVarValue("opendate")) > $now)
				continue;
				
			$answers = fetch("FETCH node WHERE link:node_top='".$poll->getNodeId()."' AND link:type='sub' AND property:class_name='poll_answer' AND property:name='".$_SESSION['murrix']['user']->id."' NODESORTBY property:version");
					
			if (count($answers) > 0)
			{
				if ($poll->getVarValue("hide_result") == "false")
				{
					$answers = fetch("FETCH node WHERE link:node_top='".$poll->getNodeId()."' AND link:type='sub' AND property:class_name='poll_answer' NODESORTBY property:version");
					
					$total = count($answers);
					
					$answer_result = array();
					
					foreach ($answers as $answer)
						$answer_result[$answer->getVarValue("answer")]++;
						
					$answer_max = 0;
					foreach ($answer_result as $ar)
					{
						if ($ar > $answer_max)
							$answer_max = $ar;
					}
					$ratio = $answer_max/$total;
					
					$alternatives = $poll->getVarValue("alternatives");
					
					?>
					<div class="poll">
						<div class="question">
							<?=$poll->getVarValue("question")?>
						</div>
						
						<div class="result">
						<?
							for ($n = 0; $n < count($alternatives); $n++)
							{
								$votes = (isset($answer_result[$n]) ? $answer_result[$n] : 0);
								$percent = round(((float)$votes/(float)$total)*100, 2);
								
								$result_str = "$percent% ($votes)";
								?>
								<div class="alternative">
									<?=$alternatives[$n]?> <?=$result_str?>
									<div class="staple" style="width:<?=floor($percent/$ratio)?>%"></div>
								</div>
							<?
							}
						?>
						</div>
					</div>
					<?
				}
			}
			else
			{
			?>
				<div class="poll">
					<div class="question">
						<?=$poll->getVarValue("question")?>
					</div>
					
					<div class="alternatives">
					<?
						if (isAnonymous())
							echo ucf(i18n("you must log in to vote"));
						else
						{
						?>
							<form name="sPoll<?=$poll->getNodeId()?>" id="sPoll<?=$poll->getNodeId()?>" action="javascript:void(null);" onsubmit="Post('poll','sPoll<?=$poll->getNodeId()?>');">
								<div>
									<input class="hidden" type="hidden" name="node_id" value="<?=$poll->getNodeId()?>"/>
									<?
									$alternatives = $poll->getVarValue("alternatives");
									
									for ($n = 0; $n < count($alternatives); $n++)
									{
									?>
										<div class="alternative">
											<input class="input" type="radio" name="answer" value="<?=$n?>">
											<?=$alternatives[$n]?>
										</div>
									<?
									}
									?>
									<input class="submit" id="submitButton" type="submit" value="<?=ucf(i18n("submit"))?>"/>
								</div>
							</form>
						<?
						}
					?>
					</div>
				</div>
			<?
			}
		}
	}
?>