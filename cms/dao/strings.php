<?php

class DaoString extends DAO {
	public	$plugin_id,
		$content_id,
		$internal_id,
		$string,
		$sort;

	function __construct() {
		$this->table_name = "strings";
	}
}

?>
