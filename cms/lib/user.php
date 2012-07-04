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

if (isset($_SESSION['user_id'])) {
	$res = DB::Query("SELECT * FROM ".DB_PREFIX."users WHERE id=".$_SESSION['user_id']);
	$user_vo = DB::Obj($res, "DaoUser");
	User::Set($user_vo);
}

?>
