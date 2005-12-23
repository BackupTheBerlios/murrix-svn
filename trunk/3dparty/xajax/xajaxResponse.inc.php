<?php
///////////////////////////////////////////////////////////////////////////////
// xajaxResponse.inc.php :: xajax XML response class
//
// xajax version 0.2
// copyright (c) 2005 by Jared White & J. Max Wilson
// http://xajax.sourceforge.net
//
// xajax is an open source PHP class library for easily creating powerful
// PHP-driven, web-based AJAX Applications. Using xajax, you can asynchronously
// call PHP functions and update the content of your your webpage without
// reloading the page.
//
// xajax is released under the terms of the LGPL license
// http://www.gnu.org/copyleft/lesser.html#SEC3
//
// This library is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 2.1 of the License, or (at your option) any later version.
//
// This library is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// Lesser General Public License for more details.
// 
// You should have received a copy of the GNU Lesser General Public
// License along with this library; if not, write to the Free Software
// Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
///////////////////////////////////////////////////////////////////////////////


// The xajaxResponse class is used to created responses to be sent back to your
// webpage.  A response contains one or more command messages for updating your page.
// Currently xajax supports five kinds of command messages:
// * Assign - sets the specified attribute of an element in your page
// * Append - appends data to the end of the specified attribute of an element in your page
// * Prepend - prepends data to teh beginning of the specified attribute of an element in your page
// * Replace - searches for and replaces data in the specified attribute of an element in your page
// * Script - runs JavaScript
// * Alert - shows an alert box with the suplied message text
// elements are identified by their HTML id
class xajaxResponse
{
	var $xml;
	var $sEncoding;

	// Constructor
	function xajaxResponse($sEncoding=XAJAX_DEFAULT_CHAR_ENCODING)
	{
		$this->setCharEncoding($sEncoding);
	}
	
	// setCharEncoding() sets the character encoding for the response
	// $sEncoding is a string containing the character encoding string to use
	// usage: $objResponse->setEncoding("iso-8859-1");
	// 		  $objResponse->setEncoding("utf-8");
	// * Note: to change the character encoding for all of the responses, set 
	// the DEFAULT_ENCODING constant near the beginning of the xajax.inc.php file
	function setCharEncoding($sEncoding)
	{
		$this->sEncoding = $sEncoding;
	}
	
	function _cmdXML($aAttributes, $sData)
	{
		$xml = "<cmd";
		foreach($aAttributes as $sAttribute => $sValue)
			$xml .= " $sAttribute=\"$sValue\"";
		if ($sData && !stristr($sData,'<![CDATA['))
			$xml .= "><![CDATA[$sData]]></cmd>";
		else if ($sData)
			$xml .= ">$sData</cmd>";
		else
			$xml .= "></cmd>";
		
		return $xml;
	}
	
	// addAssign() adds an assign command message to your xml response
	// $sTarget is a string containing the id of an HTML element
	// $sAttribute is the part of the element you wish to modify ("innerHTML", "value", etc.)
	// $sData is the data you want to set the attribute to
	// usage: $objResponse->addAssign("contentDiv","innerHTML","Some Text");
	function addAssign($sTarget,$sAttribute,$sData)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"as","t"=>$sTarget,"p"=>$sAttribute),$sData);
	}
	
	// addAppend() adds an append command message to your xml response
	// $sTarget is a string containing the id of an HTML element
	// $sAttribute is the part of the element you wish to modify ("innerHTML", "value", etc.)
	// $sData is the data you want to append to the end of the attribute
	// usage: $objResponse->addAppend("contentDiv","innerHTML","Some Text");
	function addAppend($sTarget,$sAttribute,$sData)
	{	
		$this->xml .= $this->_cmdXML(array("n"=>"ap","t"=>$sTarget,"p"=>$sAttribute),$sData);
	}
	
	// addPrepend() adds an prepend command message to your xml response
	// $sTarget is a string containing the id of an HTML element
	// $sAttribute is the part of the element you wish to modify ("innerHTML", "value", etc.)
	// $sData is the data you want to prepend to the beginning of the attribute
	// usage: $objResponse->addPrepend("contentDiv","innerHTML","Some Text");
	function addPrepend($sTarget,$sAttribute,$sData)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"pp","t"=>$sTarget,"p"=>$sAttribute),$sData);
	}
	
	// addReplace() adds an replace command message to your xml response
	// $sTarget is a string containing the id of an HTML element
	// $sAttribute is the part of the element you wish to modify ("innerHTML", "value", etc.)
	// $sSearch is a string to search for
	// $sData is a string to replace the search string when found in the attribute
	// usage: $objResponse->addReplace("contentDiv","innerHTML","text","<b>text</b>");
	function addReplace($sTarget,$sAttribute,$sSearch,$sData)
	{
		$sDta = "<s><![CDATA[$sSearch]]></s><r><![CDATA[$sData]]></r>";
		$this->xml .= $this->_cmdXML(array("n"=>"rp","t"=>$sTarget,"p"=>$sAttribute),$sDta);
	}
	
	// addClear() adds an clear command message to your xml response
	// $sTarget is a string containing the id of an HTML element
	// $sAttribute is the part of the element you wish to clear ("innerHTML", "value", etc.)
	// usage: $objResponse->addClear("contentDiv","innerHTML");
	function addClear($sTarget,$sAttribute)
	{
		$this->addAssign($sTarget,$sAttribute,'');
	}
	
	// addAlert() adds an alert command message to your xml response
	// $sMsg is a text to be displayed in the alert box
	// usage: $objResponse->addAlert("This is some text");
	function addAlert($sMsg)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"al"),$sMsg);
	}
	
	// Adds a redirect to another URL
	function addRedirect($sURL)
	{
		$this->addScript('window.location = "'.rawurlencode($sURL).'";');
	}

	// addScript() adds a jscript command message to your xml response
	// $sJS is a string containing javascript code to be executed
	// usage: $objResponse->addAlert("var x = prompt('get some text');");
	function addScript($sJS)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"js"),$sJS);
	}
	
	// addRemove() adds a Remove Element command message to your xml response
	// $sTarget is a string containing the id of an HTML element to be removed
	// from your page
	// usage: $objResponse->addRemove("Div2");
	function addRemove($sTarget)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"rm","t"=>$sTarget),'');
	}
	
	// addCreate() adds a create element command message to your xml response
	// $sParent is a string containing the id of an HTML element to which the new
	// element will be appended.
	// $sTag is the tag to be added
	// $sId is the id to be assigned to the new element
	// $sType has been deprecated, use the addCreateInput() method instead
	// from your page
	// usage: $objResponse->addRemove("Div2");
	function addCreate($sParent, $sTag, $sId, $sType="")
	{
		if ($sType)
		{
			trigger_error("The \$sType parameter of addCreate has been deprecated.  Use the addCreateInput() method instead.", E_USER_WARNING);
			return;
		}
		$this->xml .= $this->_cmdXML(array("n"=>"ce","t"=>$sParent,"p"=>$sId),$sTag);
	}
	
	// addInsert() adds a create element command message to your xml response
	// $sBefore is a string containing the id of the child before which the new element
	// will be inserted.
	// $sTag is the tag to be added
	// $sId is the id to be assigned to the new element
	// usage: $objResponse->addRemove("Div2");
	function addInsert($sBefore, $sTag, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ie","t"=>$sBefore,"p"=>$sId),$sTag);
	}
	
	// addCreateInput() adds a create input command message to your xml response
	// $sParent is a string containing the id of an HTML element to which the new
	// input will be appended.
	// $sType is the type of input to be created (text, radio, checkbox, etc.)
	// $sName is the name to be assigned to the new input and the variable name when it is submitted
	// $sId is the id to be assigned to the new input
	// usage: $objResponse->addCreateInput("form1","text","username","input1");
	function addCreateInput($sParent, $sType, $sName, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ci","t"=>$sParent,"p"=>$sId,"c"=>$sType),$sName);
	}
	
	// addInsertInput() adds a insert input command message to your xml response
	// $sParent is a string containing the id of an HTML element to which the new
	// element will be inserted.
	// $sBefore is a string containing the id of the child before which the new element
	// will be inserted.
	// $sType is the type of input to be created (text, radio, checkbox, etc.)
	// $sName is the name to be assigned to the new input and the variable name when it is submitted
	// $sId is the id to be assigned to the new input
	// usage: $objResponse->addInsertInput("input5","text","username","input1");
	function addInsertInput($sBefore, $sType, $sName, $sId)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ii","t"=>$sBefore,"p"=>$sId,"c"=>$sType),$sName);
	}
	
	// addEvent() adds an event command message to your xml response
	// $sTarget is a string containing the id of an HTML element
	// $sEvent is the event you wish to set ("onclick", "onmouseover", etc.)
	// $sScript is the javascript string you want to the event to invoke
	// usage: $objResponse->addEvent("contentDiv","click","alert(\'Hello World\');");
	function addEvent($sTarget,$sEvent,$sScript)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"ev","t"=>$sTarget,"p"=>$sEvent),$sScript);
	}
	
	// addHandler() adds a handler command message to your xml response
	// $sTarget is a string containing the id of an HTML element
	// $sEvent is the event you wish to set ("click", "mouseover", etc.)
	// $sHandler is the a string containing the name of a javascript function
	///that will handle the event. Multiple handlers can be added for the same event
	// usage: $objResponse->addHandler("contentDiv","click","content_click");
	function addHandler($sTarget,$sEvent,$sHandler)
	{	
		$this->xml .= $this->_cmdXML(array("n"=>"ah","t"=>$sTarget,"p"=>$sEvent),$sHandler);
	}
	
	// addRemoveHandler() adds a removehandler command message to your xml response
	// $sTarget is a string containing the id of an HTML element
	// $sEvent is the event you wish to remove ("click", "mouseover", etc.)
	// $sHandler is the a string containing the name of a javascript handler function
	///that you want to remove.
	// usage: $objResponse->addRemoveHandler("contentDiv","click","content_click");
	function addRemoveHandler($sTarget,$sEvent,$sHandler)
	{	
		$this->xml .= $this->_cmdXML(array("n"=>"rh","t"=>$sTarget,"p"=>$sEvent),$sHandler);
	}
	
	// addIncludeScript() adds an include command message to your xml response
	// $sFileName is the name of a javascript file to include
	// usage: $objResponse->addIncludeScript("functions.js");
	function addIncludeScript($sFileName)
	{
		$this->xml .= $this->_cmdXML(array("n"=>"in"),$sFileName);
	}
	
	// getXML() returns the xml to be returned from your function to the xajax
	// processor on your page
	// usage: $objResponse->getXML();
	function getXML()
	{
		$sXML = "<?xml version=\"1.0\"";
		if ($this->sEncoding && strlen(trim($this->sEncoding)) > 0)
			$sXML .= " encoding=\"".$this->sEncoding."\"";
		$sXML .= " ?"."><xjx>" . $this->xml . "</xjx>";
		
		return $sXML;
	}
	
	// loadXML() adds the commands of the provided response XML output to this
	// response object
	function loadXML($sXML)
	{
		$sNewXML = "";
		$iStartPos = strpos($sXML, "<xjx>") + 5;
		$sNewXML = substr($sXML, $iStartPos);
		$iEndPos = strpos($sNewXML, "</xjx>");
		$sNewXML = substr($sNewXML, 0, $iEndPos);
		$this->xml .= $sNewXML;
	}
	
}// end class xajaxResponse
?>