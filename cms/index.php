<?php

// Mark that this is a valid entry point
$SECURE = true;

// Enable / Disable debugging
$DEBUG = true;

require_once("common.php");

// Start session and check for login
session_start();
if (isset($_POST['__user']) && isset($_POST['__pass'])) {
	$username = Settings::Get("admin_username");
	$password = Settings::Get("admin_password");

	if ($_POST['__user'] == $username && $_POST['__pass'] == $password) {
		$_SESSION['logged_in'] = true;

		$site_base = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
		header("Location: ".$site_base."\n");
		exit();
	}
}
if (!(isset($_SESSION['logged_in']))) {
	require_once("login.php");
	exit();
}

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
case PAGE_PROFILE:
	require_once("page_profile.php");
	break;
}

require_once("footer.php");

?>
