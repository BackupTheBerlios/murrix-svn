<div class="main_title">
	Welcome
</div>
<table class="invisible" cellspacing="0">
	<tr>
		<td>
			<? include(gettpl("install/menu")) ?>
		</td>
		<td width="100%">
			<div class="main">
				This will take you through the necessary steps of installing MURRiX.<br/>
				<br/>
				Readme<br/>
				<iframe src="<?="$wwwpath/docs/README.txt"?>"></iframe>
			</div>
			<div class="main_nav">
				<?=cmd("Next -->", "Exec('install','zone_main',Hash('stage','2'))")?>
			</div>
		</td>
	</tr>
</table>