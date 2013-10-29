<?php

class DaoResource extends DAO {
	public	$page_id,
		$subpages,
		$plugin_id,
		$content_id,
		$internal_id,
		$group_id;

	function __construct() {
		$this->table_name = "resources";
	}
}

?>
