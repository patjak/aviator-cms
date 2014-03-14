<?php

// FIXME: Rename to "contents" or "components" or something more suitable

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

class DaoBlob extends DAO {
	public	$plugin_id,
		$content_id,
		$internal_id,
		$data,
		$sort;

	function __construct() {
		$this->table_name = "blobs";
	}
}

?>
