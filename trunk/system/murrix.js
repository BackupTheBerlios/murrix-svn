function URLDecode(indata)
{
	// Replace + with ' '
	// Replace %xx with equivalent character
	// Put [ERROR] in output if %xx is invalid.
	var HEXCHARS = "0123456789ABCDEFabcdef";
	var encoded = indata;
	var plaintext = "";
	var i = 0;

	while (i < encoded.length)
	{
		var ch = encoded.charAt(i);
		if (ch == "+")
		{
			plaintext += " ";
			i++;
		}
		else if (ch == "%")
		{
			if (i < (encoded.length-2) && HEXCHARS.indexOf(encoded.charAt(i+1)) != -1 && HEXCHARS.indexOf(encoded.charAt(i+2)) != -1)
			{
				plaintext += unescape( encoded.substr(i,3) );
				i += 3;
			}
			else
			{
				alert('Bad escape combination near ...' + encoded.substr(i));
				plaintext += "%[ERROR]";
				i++;
			}
		}
		else
		{
			plaintext += ch;
			i++;
		}
	} // while
	return plaintext;
}

var last_command = "";
var run_cmd = true;

function Poll()
{
	command = window.location.href.split("#")[1];
	if (typeof command == 'undefined')
		command = "default";

	if (last_command != command || run_cmd)
	{
		if (command == "default")
		{
			last_command = command;
			eval(URLDecode(init()));
		}
		else
		{
			//command = window.location.href.split("#")[1];

			last_command = command;
			eval(URLDecode(last_command));
			
		}
		run_cmd = false;
	}
}

function OnClickCmd(cmd)
{
	window.location.href = window.location.href.split("#")[0]+"#"+cmd;
	last_command = cmd;
	eval(URLDecode(last_command));
}

function OnLoadHandler()
{
	init();
	last_command = "default";
	setInterval("Poll()", 600);
}

function Hash()
{
	var list = new Array();
	for (var i = 0; i < arguments.length; i += 2)
	{
		if (typeof (arguments[i + 1]) != 'undefined')
		{
			list[arguments[i]] = arguments[i + 1];
		}
	}

	return list;
}

function in_array(the_needle, the_haystack)
{
	var the_hay = the_haystack.toString();
	if(the_hay == '')
	{
		return false;
	}

	var the_pattern = new RegExp(the_needle, 'g');
	var matched = the_pattern.test(the_haystack);

	return matched;
}

function triggerEvent(event, args)
{
	startScript(event);
	xajax_TriggerEvent(event, args);
	return false;
}

function Exec(scriptname, zone, args)
{
	startScript(zone);
	xajax_ExecScript(scriptname, zone, args);
	return false;
}

function Post(scriptname, zone, formname)
{
	return Exec(scriptname, zone, xajax.getFormValues(formname));
}

var active_zones = new Array();

function startScript(zone)
{
	scroll(0,0);
	if (in_array(zone, active_zones))
		return;

	active_zones.push(zone);

	if (active_zones.length > 0 && self.loading)
		loading(true, zone);
}

function endScript(zone)
{
	var list = new Array();
	var count = 0;
	for (var i = 0; i < active_zones.length; i++)
	{
		if (active_zones[i] != zone)
		{
			list[count] = active_zones[i];
			count++;
		}
	}

	active_zones = list;

	if (active_zones.length <= 0 && self.loading)
		loading(false, zone);
}
