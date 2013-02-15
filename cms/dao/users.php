<?php

class DaoUser {
	public	$id,
		$username,
		$password,
		$fullname,
		$email,
		$full_access,
		$start_page_id,
		$limited;
}

class DaoUserGroup {
	public	$id,
		$name,
		$description;
}

class DaoUserGroupMember {
	public	$id,
		$group_id,
		$user_id;
}

?>
