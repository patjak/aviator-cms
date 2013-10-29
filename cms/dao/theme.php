<?php

class DaoTheme extends DAO {
	public	$name,
		$directory;

	function __construct() {
		$this->table_name = "themes";
	}
}

?>
