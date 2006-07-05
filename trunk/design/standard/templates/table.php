<div class="listwrapper">
	<table cellspacing="0">
		<thead>
			<tr>
			<?
				foreach ($args['list'][0] as $titelname)
				{
					?><td>
						<?=$titelname?>
					</td><?
				}
			?>
			</tr>
		</thead>
		<tbody>
		<?
			if (count($args['list']) > 1)
			{
				for ($n = 1; $n < count($args['list']); $n++)
				{
					?><tr><?
					
					$class = $n%2 ? "row" : "row_selected";
					foreach ($args['list'][$n] as $data)
					{
					?>
						<td class="<?=$class?>">
							<?=$data?>
						</td>
					<?
					}
					?></tr><?
				}
			}
			else
			{
				?><tr><td colspan="<?=count($args['list'][0])?>" class="empty"></td></tr><?
			}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="<?=count($args['list'][0])?>">
					<?=str_replace("%", count($args['list'])-1, $args['endstring'])?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>