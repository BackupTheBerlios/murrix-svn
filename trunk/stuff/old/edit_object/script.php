<?

class sEdit_object extends Script
{
	function sEdit_object()
	{
	}

	function Run(&$objResponse, $zone, $arguments)
	{
		$this->object = new mObject();

		if (isset($arguments['path']))
			$this->path = $arguments['path'];
		else
			$this->path = $_SESSION['murrix']['path'];

		$this->object->SetByPath($this->path);

		$this->zone = $zone;
		$objResponse->addAssign($this->zone, "innerHTML", utf8e($this->Get($arguments)));
	}

	function Get($arguments)
	{
		require(gettpl("scripts/edit_object.php", $this->object));

		ob_start();

		if (HasRight("read", $this->path))
			DrawEditForm($this->object);
		else
			DrawNoRights();

		$show = ob_get_contents();
		ob_end_clean();
		return $show;
	}
	
	function PrintJavascript()
	{
	?>
		function sEdit_objectCall()
		{
			StartScript();
			xajax.$('submitButton').disabled = true;
			xajax.$('submitButton').value = "Please wait...";
			xajax_sEdit_objectCall(xajax.getFormValues("editForm"));
			return false;
		}
	<?
	}
	
	function Call(&$objResponse, $aFormValues)
	{
		$bError = false;
		if (trim($aFormValues['name']) == "")
		{
			$objResponse->addAlert("Please enter a name.");
			$objResponse->addAssign("submitDiv", "innerHTML", "<input id=\"submitButton\" type=\"submit\" value=\"Save\"/>");
			$bError = true;
		}
		
		if (!$bError)
		{
			$formData = array();
			foreach ($aFormValues as $key => $value)
				$formData[$key] = trim($value);
			
			$this->object->name = $formData['name'];
			
			$status = $this->object->Save($this->path, $formData);
		
			if ($status === true)
			{
				$_SESSION['murrix']['path'] = $this->object->GetPath();
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
		else
		{
			//$objResponse->addAssign("submitButton", "value", "done");
			//$objResponse->addAssign("submitButton", "disabled", false);
		}
	}
}

function sEdit_objectCall($data)
{
	return $_SESSION['murrix']['System']->CallScript("edit_object", $data);
}

?>