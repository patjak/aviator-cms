<?php

class DaoPermission extends DAO {
	public	$resource_id,
		$user_id,

		$allow_create,
		$allow_update,
		$allow_delete;

	function __construct() {
		$this->table_name = "permissions";
	}
}

?>
