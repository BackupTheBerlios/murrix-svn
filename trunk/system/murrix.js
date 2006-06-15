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
var default_command = "";

function getHash()
{
	return document.location.hash.substr(1, document.location.hash.length - 1);
}

function setHash(cmd)
{
	document.location.hash = "#"+cmd;
}

function parseCommand(cmd)
{
	var args = cmd.split("&");
	
	var script = "";
	var hash_arg = "";
	
	for (var n = 0; n < args.length; n++)
	{
		var parts = args[n].split("=");
		
		if (parts[0] == "exec")
			script = parts[1];
		else
		{
			if (hash_arg.length != 0)
				hash_arg += ",";
				
			hash_arg += "'"+parts[0]+"','"+parts[1]+"'";
		}
	}
	
	var cmd = "Exec('"+script+"',Hash("+hash_arg+"))";
	
	return cmd;
}

function Poll()
{
	command = getHash();
	
	if (typeof command == 'undefined')
		command = "default";
		
	if (command != last_command)
	{
		if (command == "default")
			eval(URLDecode(default_command));
		else
			eval(parseCommand(URLDecode(command)));
			
		last_command = command;
	}
}

function setRun()
{
	setTimeout("last_command = '';", 600);
}

function OnLoadHandler()
{
	default_command = init();
	last_command = "";
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

function Exec(scriptname, args)
{
	startScript(scriptname);
	xajax_ExecScript(scriptname, args);
	return false;
}

function Post(scriptname, formname)
{
	delEditors(formname);
	return Exec(scriptname, xajax.getFormValues(formname));
}

var active_editors = new Object();

function addEditor(formname, varid)
{
	if (typeof active_editors[formname] == 'undefined')
		active_editors[formname] = 0;

	tinyMCE.addMCEControl(xajax.$(varid),'MCEControlID_'+active_editors[formname]);
	tinyMCE.updateContent('MCEControlID_'+active_editors[formname]);
		
	active_editors[formname]++;
}

function delEditors(formname)
{
	if (typeof active_editors[formname] == 'undefined')
		active_editors[formname] = new Object();
		
	for (var n = 0; n < active_editors[formname]; n++)
	{
		var inst = tinyMCE.getInstanceById('MCEControlID_'+n);
		if (inst)
			inst.triggerSave(false, false);
		tinyMCE.removeMCEControl('MCEControlID_'+n);
	}
		
	active_editors[formname] = new Object();
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
