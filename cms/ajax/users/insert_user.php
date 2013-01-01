<?php
require_once("../include.php");

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

if ($password == "") {
	echo "<p>Password cannot be empty</p>";
	$err = true;
}

$username_safe = mysql_real_escape_string($username);
if ($username_safe != $username) {
	echo "<p>Invalid username</p>";
	$err = true;
}

$res = DB::Query("SELECT id FROM ".DB_PREFIX."users WHERE username='".$username_safe."' LIMIT 1");
if (DB::NumRows($res) > 0) {
	echo "<p>A user with that username already exists</p>";
	$err = true;
}

if ($err) {
	Ajax::SetStatus(AJAX_STATUS_NOTICE);
	exit();
}

$user = new DaoUser();
$user->fullname = $fullname;
$user->email = $email;
$user->username = $username;
$user->password = md5($password);
$user->full_access = 0; // FIXME: Add handling of full_access (admin) users

DB::Insert(DB_PREFIX."users", $user);

?>

