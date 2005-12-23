<?

class sCreate_object extends Script
{
	function sCreate_object()
	{
	}

	function Run(&$objResponse, $zone, $arguments)
	{
		$this->object = new mObject();

		if (isset($arguments['path']))
			$this->path = $arguments['path'];
		else
			$this->path = $_SESSION['murrix']['path'];

		if (isset($arguments['class']))
			$this->object->class_name = $arguments['class'];

		$this->zone = $zone;
		$objResponse->addAssign($this->zone, "innerHTML", utf8e($this->Get($arguments)));
	}

	function Get($arguments)
	{
		require(gettpl("scripts/create_script.php", $this->object));

		ob_start();

		if (HasRight("read", $this->path))
		{
			if (!empty($this->object->class_name))
			{
				$this->object->InitVars();
				DrawObjectForm($this->object);
			}
			else
				DrawClassForm();
		}
		else
			DrawNoRights();

		$show = ob_get_contents();
		ob_end_clean();
		return $show;
	}
	
	function PrintJavascript()
	{
	?>
		function sCreate_objectCall()
		{
			StartScript();
			xajax.$('submitButton').disabled = true;
			xajax.$('submitButton').value = "Please wait...";
			xajax_sCreate_objectCall(xajax.getFormValues("createForm"));
			return false;
		}
	<?
	}
	
	function Call(&$objResponse, $aFormValues)
	{
		if (isset($aFormValues['class']))
		{
			$this->object->class_name = $aFormValues['class'];
			$objResponse->addAssign($this->zone, "innerHTML", utf8e($this->Get($aFormValues)));
		}
		else
		{
			$this->Objectdata($objResponse, $aFormValues);
		}
	}
	
	function Objectdata(&$objResponse, $aFormValues)
	{
		$bError = false;
		if (trim($aFormValues['name']) == "")
		{
			$objResponse->addAlert("Please enter a name.");
			$objResponse->addAssign("submitDiv", "innerHTML", "<input id=\"submitButton\" type=\"submit\" value=\"Create\"/>");
			$bError = true;
		}
		
		if (!$bError)
		{
			$formData = array();
			foreach ($aFormValues as $key => $value)
				$formData[$key] = trim($value);
			
			$this->object->name = $formData['name'];

			$status = $this->object->Save($this->path, $formData);
			
			unset($this->object);

			if ($status === true)
			{
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

function sCreate_objectCall($data)
{
	return $_SESSION['murrix']['System']->CallScript("create_object", $data);
}

?>