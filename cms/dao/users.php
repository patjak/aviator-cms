<?php

class DaoUser {
	public	$id,
		$username,
		$password,
		$fullname,
		$email,
		$full_access;
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
