<?php
require_once("../include.php");

$uid = (int)$_POST['uid'];

if (count($res) == 0)
	exit();

$user_vo = DB::ObjByID("DaoUser", $uid);

if ($user_vo->full_access != 0) {
	echo "<p>You cannot delete an administrator!</p>";
	Ajax::SetStatus(AJAX_STATUS_NOTICE);
	exit();
}

DB::Query("DELETE FROM ".DB_PREFIX."users WHERE id=".$uid);
?>
