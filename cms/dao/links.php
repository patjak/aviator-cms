<?php

class DaoLink extends DAO {
	public	$name,
		$is_internal,
		$internal_page_id,
		$external_url,
		$in_new_window,
		$enabled,

		$plugin_id,
		$content_id,
		$internal_id,
		$sort;

	function __construct() {
		$this->table_name = "links";
	}
}
