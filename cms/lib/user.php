<?php

class User {
	static private $user_vo = false;

	static public function Get()
	{
		return User::$user_vo;
	}

	static public function Set($user_vo)
	{
		User::$user_vo = $user_vo;
	}
}

?>
