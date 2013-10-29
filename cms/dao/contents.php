<?php

class DaoContent extends DAO {
	public	$plugin_id,
		$page_id,
		$name,
		$sort,
		$section_id,
		$internal_id;

	function __construct() {
		$this->table_name = "contents";
	}
}

/*
class DaoContentString extends DAO {
	public	$plugin_id,
		$content_id,
		$internal_id,
		$string;
}

*/
?>
