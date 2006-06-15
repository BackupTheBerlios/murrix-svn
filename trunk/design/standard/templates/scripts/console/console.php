<?/*<script type="text/javascript">
<!--
	var console_history = new Array();
	var console_item = 0;
	
	function addToHistory()
	{
		console_history.push(document.getElementById('cmdline').value);
		console_item = console_history.length-1;
	}
	
	function onKeyUpCmdline
	{
		//alert(event.keyCode)
		var code = event.keyCode
		switch (code)
		{
		case 38: // up
			console_item++;
			document.getElementById('cmdline').value = console_history[console_item];
			break
		case 40: // down
			console_item--;
			document.getElementById('cmdline').value = console_history[console_item];
			break
		}
	}
	
// -->
</script>
*/?>
<div id="console_wrapper">
	<div id="console_log" onmouseup="document.getElementById('cmdline').focus();">
		<div class="title">
			Welcome to the MURRiX administration console<br/>
			============================================
		</div>
		<?=$logtext?>
	</div>
	<div id="console_cmd">
		<form id="sConsoleCmd" action="javascript:void(null);" onsubmit="Post('console','sConsoleCmd')">
			<input autocomplete="off" id="cmdline" name="cmdline" type="text"/>
		</form>
	</div>
</div>