<?php

class DaoAccessLog extends DAO {
	public	$timestamp,
		$user_id,
		$permission_id,
		$type;

	function __construct() {
		$this->table_name = "access_logs";
	}
}

?>
