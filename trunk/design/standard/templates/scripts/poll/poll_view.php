<?

$answers = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='poll_answer' AND property:name='".$_SESSION['murrix']['user']->id."' NODESORTBY property:version");

if (count($answers) > 0)
{
	if ($object->getVarValue("hide_result") == "false")
	{
		$answers_all = fetch("FETCH node WHERE link:node_top='".$object->getNodeId()."' AND link:type='sub' AND property:class_name='poll_answer' NODESORTBY property:version");
		
		$answers = array();
			
		foreach ($answers_all as $answer)
		{
			if (!isset($answers[$answer->getName()]))
				$answers[$answer->getName()] = $answer;
			else
			{
				clearNodeFileCache($answer->getNodeId());
				$answer->deleteNode();
			}
		}
		
		$answers = array_values($answers);
		
		$total = count($answers);
		
		$answer_result = array();
		
		foreach ($answers as $answer)
			$answer_result[$answer->getVarValue("answer")]++;
			
		/*$answer_max = 0;
		foreach ($answer_result as $ar)
		{
			if ($ar > $answer_max)
				$answer_max = $ar;
		}
		$ratio = $answer_max/$total;*/
		
		$alternatives = $object->getVarValue("alternatives");
		
		?>
		<div class="poll">
			<div class="date">
				<?=$object->getVarValue("opendate")." - ".$object->getVarValue("closedate")?>
			</div>
			<div class="question">
				<?=$object->getVarValue("question")?>
			</div>
			
			<div class="result">
			<?
				for ($n = 0; $n < count($alternatives); $n++)
				{
					$votes = (isset($answer_result[$n]) ? $answer_result[$n] : 0);
					$percent = round(((float)$votes/(float)$total)*100, 2);
					
					$result_str = "$percent% / $votes ".i18n("vote(s)");
					?>
					<div class="alternative">
						<?=$alternatives[$n]?> - <?=$result_str?>
						<div class="staple_wrapper">
							<div class="staple" style="width:<?=floor($percent)?>%"></div>
						</div>
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
		<div class="date">
			<?=$object->getVarValue("opendate")." - ".$object->getVarValue("closedate")?>
		</div>
		<div class="question">
			<?=$object->getVarValue("question")?>
		</div>
		
		<div class="alternatives">
		<?
			if (isAnonymous())
				echo ucf(i18n("you must log in to vote"));
			else
			{
			?>
				<form name="sPoll<?=$object->getNodeId()?>" id="sPoll<?=$object->getNodeId()?>" action="javascript:void(null);" onsubmit="Post('poll','sPoll<?=$object->getNodeId()?>');">
					<div>
						<input class="hidden" type="hidden" name="node_id" value="<?=$object->getNodeId()?>"/>
						<?
						$alternatives = $object->getVarValue("alternatives");
						
						for ($n = 0; $n < count($alternatives); $n++)
						{
						?>
							<div class="alternative">
								<input class="input_radio" type="radio" name="answer" value="<?=$n?>">
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
?>