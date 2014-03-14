<?php
require_once("../include.php");

$gid = (int)$_POST['gid'];

$res = DB::Query("SELECT * FROM ".DB_PREFIX."user_groups WHERE id=".$gid);
if (DB::NumRows($res) == 0)
	exit();

$group_vo = DB::Obj($res, "DaoUserGroup");

DB::Query("DELETE FROM ".DB_PREFIX."user_groups WHERE id=".$gid);
?>
