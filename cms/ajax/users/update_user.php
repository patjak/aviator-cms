<?php
require_once("../include.php");

$uid = (int)$_POST['uid'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

// Validate
$err = false;

if ($username == "") {
	echo "<p>Username cannot be empty</p>";
	$err = true;
}

$res = DB::Query("SELECT id FROM ".DB_PREFIX."users WHERE username='".$username."' LIMIT 1");
if (count($res) > 0) {
	$row = $res[0];

	if ($row[0] != $uid) {
		echo "<p>A user with that username already exists</p>";
		$err = true;
	}
}

$user = DB::ObjByID("DaoUser", $uid);

if ($user->full_access != 0) {
	echo "<p>You cannot change administrator accounts</p>";
	$err = true;
}

if ($err) {
	Ajax::SetStatus(AJAX_STATUS_NOTICE);
	exit();
}

$user->fullname = $fullname;
$user->email = $email;
$user->username = $username;
if ($password != "")
	$user->password = md5($password);

DB::Update(DB_PREFIX."users", $user);

?>
