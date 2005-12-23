<?

class sLink_object extends Script
{
	function sLink_object()
	{
	}

	function Get($arguments)
	{
		unset($_SESSION['murrix']['script']);

		if (!isset($arguments['path']))
			$_SESSION['murrix']['script']['path'] = $_SESSION['murrix']['path'];
		else
			$_SESSION['murrix']['script']['path'] = $arguments['path'];

		ob_start();
		if (HasRight("edit", $_SESSION['murrix']['script']['path']))
		{
			$object = new mObject();
			$object->SetByPath($_SESSION['murrix']['script']['path']);
			guiTitel("Link Object", "&nbsp;");
			?>
			<table class="simple" cellspacing="0" width="100%">
				<tr>
					<td class="simplemain">
						<form id="linkForm" action="javascript:void(null);" onsubmit="sLink_objectCall();">
							Link <?=$object->name?> to:
							<input type="text" name="linkpath" value="">
							<select name="linktype">
								<option value="sub">As subfolder</option>
								<option value="partner">As partner</option>
								<option value="child">As child</option>
								<option value="data">As related data</option>
							</select>
							<div id="submitDiv" class="submitDiv"><input id="submitButton" type="submit" value="Link"/></div>
						</form>
					</td>
				</tr>
			</table>
		<?
		}
		else
		{
			guiTitel("No rights", "&nbsp;");
			?>
			<table class="simple" cellspacing="0" width="100%">
				<tr>
					<td class="simplemain">
						You do not have enough rights to link this object.
					</td>
				</tr>
			</table>
			<?
		}
		$show = ob_get_contents();
		ob_end_clean();
		return $show;
	}
	
	function PrintJavascript()
	{
	?>
		function sLink_objectCall()
		{
			StartScript();
			xajax.$('submitButton').disabled = true;
			xajax.$('submitButton').value = "Please wait...";
			xajax_sLink_objectCall(xajax.getFormValues("linkForm"));
			return false;
		}
	<?
	}
	
	function Call(&$objResponse, $aFormValues)
	{
		$linkpath = $aFormValues['linkpath'];
		$linktype = $aFormValues['linktype'];
		
		$object = new mObject();
		$object->SetByPath($_SESSION['murrix']['script']['path']);
			
		$parent_id = ResolvePath($linkpath);
						
		if ($parent_id === false)
			$status = "$linkpath. No such path to link to.";
		else
		{
			$query = "INSERT INTO `relations` (obj2_id, obj1_id, type) VALUES('$object->id', '$parent_id', '$linktype')";
			
			$result = mysql_query($query);
			if (!$result)
			{
				$status = "<b>An error occured while inserting</b><br>";
				$status .= "<b>Table:</b> relations<br>";
				$status .= "<b>Query:</b> $query<br>";
				$status .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
				$status .= "<b>Error:</b> " . mysql_error() . "<br>";
			}
			else
				$status = true;
		}
				
		if ($status === true)
		{
			unset($_SESSION['murrix']['script']);
			$_SESSION['murrix']['System']->RunScriptIntern($objResponse, "show", $this->zone, array('path' => $copypath));
		}
		else
		{
			$message = "Operation unsuccessfull.<br/>";
			$message .= "Error output:<br/>";
			$message .= $status;
			$objResponse->addAlert($message);
		}
	}
}

function sLink_objectCall($data)
{
	return $_SESSION['murrix']['System']->CallScript("link_object", $data);
}

?>