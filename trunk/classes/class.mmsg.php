<?

class mMsg
{
	function mMsg()
	{
		$this->clearAll();
	}
	
	function clearAll()
	{
		$GLOBALS['murrix']['messages'] = array(array("caller" => "mMsg", "text" => "no such message found", "error" => 0));
	}

	function add($caller, $text, $error = true)
	{
		$message = array();
		$message['caller'] = $caller;
		$message['text'] = $text;
		$message['error'] = $error ? 1 : 0;
		
		$id = count($GLOBALS['murrix']['messages']);
		
		$GLOBALS['murrix']['messages'][$id] = $message;
		
		if ($error)
			return -$id;
		else
			return $id;
	}
	
	function isError($id)
	{
		if ($id < 0)
			$id = -$id;
			
		if (isset($GLOBALS['murrix']['messages'][$id]))
			return $GLOBALS['murrix']['messages'][$id];
		
		return false;
	}
	
	function get($id)
	{
		if ($id < 0)
			$id = -$id;
	
		if (isset($GLOBALS['murrix']['messages'][$id]))
			return $GLOBALS['murrix']['messages'][$id];
		
		return $GLOBALS['murrix']['messages'][0];
	}
	
	function getText($id)
	{
		if ($id < 0)
			$id = -$id;
	
		if (isset($GLOBALS['murrix']['messages'][$id]))
			return $GLOBALS['murrix']['messages'][$id]['text'];
		
		return $GLOBALS['murrix']['messages'][0]['text'];
	}
}

?>