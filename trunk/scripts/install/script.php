<?

class sInstall extends Script
{
	function sInstall()
	{
		$this->admin_password = "";
		$this->admin_username = "admin";
		$this->db_address = "localhost";
		$this->db_name = "murrix";
		$this->db_prefix = "murrix_";
		$this->db_username = "";
		$this->db_password = "";
		$this->site = "standard";
	}
	
	function Exec(&$system, &$response, $args)
	{
		if (!isset($args['stage']))
			$args['stage'] = 1;

		if (isset($args['admin_username']))
			$this->admin_username = $args['admin_username'];

		if (isset($args['admin_password1']) || isset($args['admin_password2']))
		{
			if ($args['admin_password1'] != $args['admin_password2'])
			{
				$response->addAlert("Passwords don't match, please try again.");
				return;
			}
			$this->admin_password = $args['admin_password1'];
		}

		if (isset($args['db_address']))
			$this->db_address = $args['db_address'];

		if (isset($args['db_name']))
			$this->db_name = $args['db_name'];

		if (isset($args['db_prefix']))
			$this->db_prefix = $args['db_prefix'];

		if (isset($args['db_username']))
			$this->db_username = $args['db_username'];

		if (isset($args['db_password']))
			$this->db_password = $args['db_password'];

		if (isset($args['site']))
			$this->site = $args['site'];

		if ($args['stage'] == 4)
		{
			if (empty($this->admin_username) || empty($this->admin_password))
			{
				$response->addAlert("Please fill in all fields.");
				return;
			}
		}
		else if ($args['stage'] == 5)
		{
			if (empty($this->db_address) || empty($this->db_name) || empty($this->db_username) || empty($this->db_password))
			{
				$response->addAlert("Please fill in all fields. Table prefix is optional.");
				return;
			}

			$this->db_log = "";

			if (!@$db_conn = mysql_pconnect($this->db_address, $this->db_username, $this->db_password))
			{
				$this->db_login = false;
				$this->db_log .= "Error connecting to database: " . mysql_errno() . " " . mysql_error()."<br/>";
			}
			else
			{
				$this->db_login = true;
				$this->db_tables = false;
				
				if (mysql_select_db($this->db_name))
				{
					$this->db_exists = true;
					$this->db_log .= "Database ".$this->db_name." exists.<br/>";

					$tables = array("classes", "links", "meta", "nodes", "objects", "pathcache", "thumbnails", "values", "vars");
					foreach ($tables as $table)
					{
						if (mysql_query("SELECT * FROM `".$this->db_prefix."$table"))
						{
							$this->db_log .= "Table ".$this->db_prefix."$table exists.<br/>";
							$this->db_tables = true;
						}
					}
				}
				else
				{
					$this->db_exists = false;
				}
			}

		}
		else if ($args['stage'] == 7)
		{
			// Create database

			// Create tables

			// Insert initial objects

		}
		
		$this->Draw($system, $response, $args);
	}
	
	function Draw(&$system, &$response, $args)
	{
		global $wwwpath, $abspath;
		ob_start();
		include(gettpl("install/stage".$args['stage']));
		$response->addAssign($this->zone, "innerHTML", utf8e(ob_get_end()));
	}
}
?>