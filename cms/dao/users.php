<?php

class DaoUser extends DAO {
	public	$username,
		$password,
		$fullname,
		$email,
		$full_access,
		$start_page_id,
		$limited;

	function __construct() {
		$this->table_name = "users";
	}
}

class DaoUserGroup extends DAO {
	public	$name,
		$description;

	function __construct() {
		$this->table_name = "user_groups";
	}
}

class DaoUserGroupMember extends DAO {
	public	$group_id,
		$user_id;

	function __construct() {
		$this->table_name = "user_group_members";
	}
}

?>
