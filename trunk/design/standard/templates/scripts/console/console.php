<div id="console_wrapper">
	<div id="console_log" onmouseup="document.getElementById('cmdline').focus();">
		<div class="title">
			Welcome to the MURRiX administration console<br/>
			============================================
		</div>
	</div>
	<div id="console_cmd">
		<form id="sConsoleCmd" action="javascript:void(null);" onsubmit="Post('console', 'zone_login', 'sConsoleCmd');">
			<input autocomplete="off" id="cmdline" name="cmdline" type="text"/>
		</form>
	</div>
</div>