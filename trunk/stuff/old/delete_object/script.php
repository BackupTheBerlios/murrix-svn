<?

class sDelete_object extends Script
{
	function sDelete_object()
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
		if (HasRight("delete", $_SESSION['murrix']['script']['path']))
		{
			$object = new mObject();
			$object->SetByPath($_SESSION['murrix']['script']['path']);
		
			guiTitel("Delete Object", "&nbsp;");
			?>
			<table class="simple" cellspacing="0" width="100%">
				<tr>
					<td class="simplemain">
						Are you sure you want to delete "<?=$object->name?>"?
						
						<form id="deleteForm" action="javascript:void(null);" onsubmit="sDelete_objectCall();">
							<div id="submitDiv" class="submitDiv"><input id="submitButton" type="submit" value="Yes"/></div>
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
						You do not have enough rights to delete this object. <?=$_SESSION['murrix']['script']['path']?>
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
		function sDelete_objectCall()
		{
			StartScript();
			xajax.$('submitButton').disabled = true;
			xajax.$('submitButton').value = "Please wait...";
			xajax_sDelete_objectCall(xajax.getFormValues("deleteForm"));
			return false;
		}
	<?
	}
	
	function Call(&$objResponse, $aFormValues)
	{
		$object = new mObject();
		$object->SetByPath($_SESSION['murrix']['script']['path']);

		$status = $object->Remove();

		if ($status === true)
		{
			$_SESSION['murrix']['path'] = GetParentPath($_SESSION['murrix']['script']['path']);
			unset($_SESSION['murrix']['script']);
			$_SESSION['murrix']['System']->RunScriptIntern($objResponse, "show", $this->zone);
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

function sDelete_objectCall($data)
{
	return $_SESSION['murrix']['System']->CallScript("delete_object", $data);
}

?>