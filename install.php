<?php

$SECURE = true;

require_once("common.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="style/style.css"/>
<link href='http://fonts.googleapis.com/css?family=Gudea:700' rel='stylesheet' type='text/css'>
<title>Noorsbron CMS 5.0</title>
</head>
<body style="background-color: #dddddd;">
<div style="text-align: center; font-size: 16pt; font-family: 'Gudea', sans-serif; margin-top: 100px;">
<?php

$install_finished = Settings::Get("install_finished");

if ($install_finished === false) {
	Settings::Set("site_title", "My new website");
	Settings::Set("site_start_page", "0");
	Settings::Set("max_page_depth", "0");
	Settings::Set("max_top_level_pages", "0");
	Settings::Set("show_contents_menu", "1");
	Settings::Set("show_modules_menu", "1");
	Settings::Set("show_themes_menu", "1");
	Settings::Set("show_plugins_menu", "1");
	Settings::Set("lock_site_tree", "0");
	Settings::Set("enable_page_rules", "0");
	Settings::Set("allow_change_start_page", "1");
	Settings::Set("install_finished", "1");

	// FIXME: Let user specify credentials for the admin account
	$res = DB::Query("SELECT id FROM ".DB_PREFIX."users WHERE username='admin'");
	if (DB::NumRows($res) == 0) {
		$admin_vo = new DaoUser();
		$admin_vo->username = "admin";
		$admin_vo->password = md5("password");
		$admin_vo->fullname = "Administrator";
		$admin_vo->full_access = 1;
		DB::Insert(DB_PREFIX."users", $admin_vo);
	}

	echo "<img src=\"".CMS_BASE."pics/icons_64/settings.png\" style=\"margin: 10px;\"/><br/>".
	"Installation completed successfully";
} else {
	echo "<img src=\"".CMS_BASE."pics/icons_64/warning.png\" style=\"margin: 10px;\"/><br/>".
	"Installation has already been completed";
}

?>
</div>
