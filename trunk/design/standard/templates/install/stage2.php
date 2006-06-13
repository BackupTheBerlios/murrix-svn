<div class="main_title">
	License
</div>
<table class="invisible" cellspacing="0">
	<tr>
		<td>
			<? include(gettpl("install/menu")) ?>
		</td>
		<td width="100%">
			<div class="main">
				MURRiX is released under the <a target="top" href="http://www.gnu.org/copyleft/gpl.html">GNU GENERAL PUBLIC LICENSE</a>.<br/><br/>
				License<br/>
				<iframe src="<?="$wwwpath/docs/LICENSE.txt"?>"></iframe>
			</div>
			<div class="main_nav">
				<?=cmd("<-- Back", "Exec('install','zone_main',Hash('stage','1'))")?>
				|
				<?=cmd("Next -->", "Exec('install','zone_main',Hash('stage','3'))")?>
			</div>
		</td>
	</tr>
</table>