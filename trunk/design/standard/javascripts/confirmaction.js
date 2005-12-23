 
/**
* Displays an confirmation box before doing some action 
*
* @param   object   the message to display 
*
* @return  boolean  whether to run the query or not
*/
function confirmAction(theMessage)
{
	// TODO: Confirmation is not required in the configuration file
	// or browser is Opera (crappy js implementation)
	if (typeof(window.opera) != 'undefined')
	{
		return true;
	}
	
	var is_confirmed = confirm(theMessage);
	
	return is_confirmed;
} // end of the 'confirmAction()' function