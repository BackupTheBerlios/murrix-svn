
function invertDisplay(id)
{
	if (document.getElementById(id).style.display == "none")
		document.getElementById(id).style.display = "block";
	else
		document.getElementById(id).style.display = "none";
}

function blockDisplay(id)
{
	document.getElementById(id).style.display = "block";
}

function noneDisplay(id)
{
	document.getElementById(id).style.display = "none";
}

function checkUncheckAll(theElement)
{
	var theForm = theElement.form;
	
	for (var z = 0; z < theForm.length; z++)
	{
		if(theForm[z].type == 'checkbox')
		{
			theForm[z].checked = !theForm[z].checked;
		}
	}
}

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
	if (cmd.length > 0)
		document.location.hash = cmd;
}

function setHref(cmd)
{
	if (cmd.length > 0)
		document.location.href = '?'+cmd;
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
	
	var cmd = "Exec('"+cmd+"','"+script+"',Hash("+hash_arg+"))";
	return cmd;
}

var poll_intervall;

var scroll_positions = new Array();
var scroll_set = 0;

function Poll()
{
	command = getHash();
	
	if (typeof command == 'undefined')
		command = "default";
		
	if (command != last_command)
	{
		scroll_positions[last_command] = (document.all)?document.body.scrollTop:window.pageYOffset;
		
		if (command == "default" || command == "")
			eval(parseCommand(URLDecode(default_command)));
		else
			eval(parseCommand(URLDecode(command)));
		
		scroll_set = -1;
		last_command = command;
	}
}

function setRun(cmd)
{
	scroll_set = 0;
	scroll_positions[last_command] = (document.all)?document.body.scrollTop:window.pageYOffset;
	
	clearInterval(poll_intervall);
	
	setHash(cmd);
	
	eval(parseCommand(URLDecode(cmd)));
	last_command = cmd;
	
	
	setTimeout("runTimeout()", 800);
}

function runTimeout()
{
	poll_intervall = setInterval("Poll()", 600);
}

function OnLoadHandler()
{
	default_command = getDefaultCommand();
	last_command = "";
	poll_intervall = setInterval("Poll()", 600);
	
	//setInterval("xajax_TriggerEvent('poll','')", 60000);
	
	runZoneJS();
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

function Exec(cmd, scriptname, args)
{
	startScript(scriptname);
	xajax_ExecScript(cmd, scriptname, args);
	return false;
}

function Post(scriptname, formname)
{
	if (typeof(formname) == "string")
		objForm = xajax.$(formname);
	else
		objForm = formname;
		
	for (var i = 0; i < objForm.elements.length; i++)
	{
		var oNode = document.getElementById(objForm.elements[i].name+'___Frame');
		if (oNode)
		{
			var oEditor = FCKeditorAPI.GetInstance(objForm.elements[i].name);
			oEditor.UpdateLinkedField()
		}
	}
	
	return Exec('', scriptname, xajax.getFormValues(objForm));
}

function openCalendar(varid, buttonid)
{
	var calendar = new CalendarPopup('popupCalendarDiv');
	calendar.setWeekStartDay(1);
	calendar.select(document.getElementById(varid), buttonid, 'yyyy-MM-dd');
}

var active_zones = new Array();

function startScript(zone)
{
	if (in_array(zone, active_zones))
		return;

	active_zones.push(zone);

	if (active_zones.length > 0 && self.loading)
		loading(true, zone);
}

function endScript(cmd, zone)
{
	if (zone == "zone_main" && scroll_set != -1)
	{
		scroll(0, scroll_set);
	}
		
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
