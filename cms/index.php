<?php

// Mark that this is a valid entry point
$SECURE = true;

// Enable / Disable debugging
$DEBUG = true;

session_start();
require_once("common.php");

/*
echo "<p>http://".$_SERVER['SERVER_NAME']."</p>";
echo "<p>".$_SERVER['PHP_SELF']."</p>";
echo "<p>".CMS_BASE."</p>";
exit();
*/

// Redirect us to the proper URL
if (strpos(CMS_BASE, SITE_PROTOCOL.$_SERVER['SERVER_NAME']) === false) {
	header("Location: ".CMS_BASE."\n");
	exit();
}

// Start session and check for login
if (isset($_POST['__user']) && isset($_POST['__pass'])) {
	$res = DB::Query("SELECT id, username, password FROM ".DB_PREFIX."users");
	$username = $_POST['__user'];
	$password = md5($_POST['__pass']);

	foreach ($res as $row) {
		if ($row['username'] == $username && $row['password'] == $password) {
			$_SESSION['user_id'] = $row['id'];

			$site_base = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
			header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
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
	require_once("pages/home.php");
	break;
case PAGE_CONTENTS:
	require_once("pages/contents.php");
	break;
case PAGE_CONTENTS_EDIT:
	require_once("pages/contents_edit.php");
	break;
case PAGE_MODULES:
	require_once("pages/modules.php");
	break;
case PAGE_PLUGINS:
	require_once("pages/plugins.php");
	break;
case PAGE_SETUP:
	require_once("pages/site_setup.php");
	break;
case PAGE_USERS:
	require_once("pages/users.php");
	break;
case PAGE_PROFILE:
	require_once("pages/profile.php");
	break;
case PAGE_IMAGE_ARCHIVE:
	require_once("pages/image_archive.php");
	break;
}

require_once("footer.php");

?>
