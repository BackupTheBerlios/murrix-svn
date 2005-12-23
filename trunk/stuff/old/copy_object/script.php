<?

class sCopy_object extends Script
{
	function sCopy_object()
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
		
			guiTitel("Copy Object", "&nbsp;");
			?>
			<table class="simple" cellspacing="0" width="100%">
				<tr>
					<td class="simplemain">
						<form id="copyForm" action="javascript:void(null);" onsubmit="sCopy_objectCall();">
							Copy <?=$object->name?> to:
							<input type="text" name="copypath" value="">
							<div id="submitDiv" class="submitDiv"><input id="submitButton" type="submit" value="Copy"/></div>
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
						You do not have enough rights to copy this object.
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
		function sCopy_objectCall()
		{
			StartScript();
			xajax.$('submitButton').disabled = true;
			xajax.$('submitButton').value = "Please wait...";
			xajax_sCopy_objectCall(xajax.getFormValues("copyForm"));
			return false;
		}
	<?
	}
	
	function Call(&$objResponse, $aFormValues)
	{
		$copypath = $aFormValues['copypath'];
		
		$object = new mObject();
		$object->SetByPath($_SESSION['murrix']['script']['path']);
		$vars = $object->GetVars();
		
		$DataArray = array();
		foreach ($vars as $var)
			$DataArray[$var->id] = $var->GetValue($object->id, true);
		
		$object->id = 0;
		$status = $object->Save($copypath, $DataArray);
				
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

function sCopy_objectCall($data)
{
	return $_SESSION['murrix']['System']->CallScript("copy_object", $data);
}

?>