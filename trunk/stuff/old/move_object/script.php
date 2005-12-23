<?

class sMove_object extends Script
{
	function sMove_object()
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
		
			guiTitel("Move Object", "&nbsp;");
			?>
			<table class="simple" cellspacing="0" width="100%">
				<tr>
					<td class="simplemain">
						<form id="moveForm" action="javascript:void(null);" onsubmit="sMove_objectCall();">
							Move <?=$object->name?> to:
							<input type="text" name="movepath" value="">
							<div id="submitDiv" class="submitDiv"><input id="submitButton" type="submit" value="Move"/></div>
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
						You do not have enough rights to move this object.
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
		function sMove_objectCall()
		{
			StartScript();
			xajax.$('submitButton').disabled = true;
			xajax.$('submitButton').value = "Please wait...";
			xajax_sMove_objectCall(xajax.getFormValues("moveForm"));
			return false;
		}
	<?
	}
	
	function Call(&$objResponse, $aFormValues)
	{
		$movepath = $aFormValues['movepath'];
		
		$object = new mObject();
		$object->SetByPath($_SESSION['murrix']['script']['path']);
			
		$oldparent_id = ResolvePath(GetParentPath($object->GetPath()));
					
		$query = "DELETE FROM `relations` WHERE ((obj1_id = '$object->id' AND obj2_id = '$oldparent_id') OR (obj2_id = '$object->id' AND obj1_id = '$oldparent_id')) AND type = 'sub'";
	
		$result = mysql_query($query);
		if (!$result)
		{
			$status = "<b>An error occured while deleting</b><br>";
			$status .= "<b>Table:</b> relations<br>";
			$status .= "<b>Query:</b> $query<br>";
			$status .= "<b>Error Num:</b> " . mysql_errno() . "<br>";
			$status .= "<b>Error:</b> " . mysql_error() . "<br>";
		}
		else
		{
			$parent_id = ResolvePath($movepath);
			
			if ($parent_id === false)
				$status = "$movepath. No such path to move to.";
			else
			{
				$query = "INSERT INTO `relations` (obj2_id, obj1_id, type) VALUES('$object->id', '$parent_id', 'sub')";
		
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

function sMove_objectCall($data)
{
	return $_SESSION['murrix']['System']->CallScript("move_object", $data);
}

?>