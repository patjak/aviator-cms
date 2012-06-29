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
	Settings::Set("max_page_depth", "0");
	Settings::Set("max_top_level_pages", "0");
	Settings::Set("show_contents_menu", "1");
	Settings::Set("show_modules_menu", "1");
	Settings::Set("show_themes_menu", "1");
	Settings::Set("show_plugins_menu", "1");
	Settings::Set("lock_site_tree", "0");
	Settings::Set("enable_page_rules", "0");
	Settings::Set("allow_change_start_page", "1");
	Settings::Set("admin_username", "patrik");
	Settings::Set("admin_password", "abcd1234");
	Settings::Set("install_finished", "1");
	echo "<img src=\"pics/icons_64/settings.png\" style=\"margin: 10px;\"/><br/>".
	"Installation completed successfully";
} else {
	echo "<img src=\"pics/icons_64/warning.png\" style=\"margin: 10px;\"/><br/>".
	"Installation has already been completed";
}

?>
</div>
