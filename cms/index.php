<?php

// Mark that this is a valid entry point
$SECURE = true;

// Enable / Disable debugging
$DEBUG = true;

session_start();
require_once("common.php");

// Start session and check for login
if (isset($_POST['__user']) && isset($_POST['__pass'])) {
	$res = DB::Query("SELECT id, username, password FROM ".DB_PREFIX."users");
	$username = $_POST['__user'];
	$password = md5($_POST['__pass']);

	while ($row = DB::Row($res)) {
		if ($row[1] == $username && $row[2] == $password) {
			$_SESSION['user_id'] = $row[0];

			$site_base = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
			header("Location: ".$site_base."\n");
			exit();
		}
	}
}

if (!(isset($_SESSION['user_id']))) {
	require_once("login.php");
	exit();
}

require_once(CMS_PATH."settings.php");

// Start displaying stuff on screen
$page = URL::GetPage();

require_once("header.php");

switch ($page) {
case PAGE_HOME:
	require_once("page_home.php");
	break;
case PAGE_CONTENTS:
	require_once("page_contents.php");
	break;
case PAGE_CONTENTS_EDIT:
	require_once("page_contents_edit.php");
	break;
case PAGE_MODULES:
	require_once("page_modules.php");
	break;
case PAGE_PLUGINS:
	require_once("page_plugins.php");
	break;
case PAGE_SETUP:
	require_once("page_site_setup.php");
	break;
case PAGE_USERS:
	require_once("page_users.php");
	break;
case PAGE_PROFILE:
	require_once("page_profile.php");
	break;
}

require_once("footer.php");

?>
