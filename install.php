<?php

$SECURE = true;

define("TMP_SITE_PATH", dirname(__FILE__)."/");
define("TMP_SITE_BASE", "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME'])."/");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="cms/style/style.css"/>
<title>Noorsbron CMS 5.0</title>
<style type="text/css">

input[type=text], input[type=password] {
	width: 250px;
	font-size: 10pt;
}

table {
	margin-bottom: 40px;
}

</style>
</head>
<body style="background-color: #dddddd; text-align: center;">
<?php

if (!file_exists("config.php")) {

	if (!isset($_POST['do_install'])) {
		// Print install form
?>
<div class="Box" style="width: 400px; text-align: left; margin: 20px; margin-left: auto; margin-right: auto;">
<form action="install.php" method="POST">
<input type="hidden" name="do_install" value="1"/>
<h2><img src="cms/pics/icons_32/settings.png"/>Setup your new website</h2>
<div class="Heading">General information</div>
<table>
<tr><td style="width: 120px;">Site name</td><td><input type="text" name="site_name" value="My website"/></td></tr>
<tr><td>Theme directory</td><td><input type="text" name="theme_dir" value="flow/"/></td></tr>
<tr><td>Admin username</td><td><input type="text" name="admin_user" value="admin"/></td></tr>
<tr><td>Admin password</td><td><input type="text" name="admin_pass" value=""/></td></tr>
</table>

<div class="Heading">Database information</div>
<p>The following information can be obtained from your hosting company or your web server administrator.</p>
<table>
<tr><td style="width: 120px;">Host</td><td><input type="text" name="db_host"/></td><td></td></tr>
<tr><td>Username</td><td><input type="text" name="db_user"/></td></tr>
<tr><td>Password</td><td><input type="text" name="db_pass"/></td></tr>
<tr><td>Name</td><td><input type="text" name="db_name"/></td></tr>
<tr><td>Table prefix</td><td><input type="text" name="db_prefix"/><i>(Leave empty for default table names)</i></td></tr>
</table>

<div class="Heading">Directory information</div>
<p>The following information can normally be auto-detected. Only change if you have a custom setup.</p>
<table>
<tr><td style="width: 120px;">Site base</td><td><input type="text" name="site_base" value="<?php echo TMP_SITE_BASE;?>"/></td></tr>
<tr><td>Site path</td><td><input type="text" name="site_path" value="<?php echo TMP_SITE_PATH;?>"/></td></tr>
<tr><td>Media directory</td><td><input type="text" name="media_dir" value="media/"/><i>(Relative to your site base and path)</i></td></tr>
</table>
<div class="Heading"></div>
<div style="text-align: center;"><input type="submit" value="Continue"/></div>
<?php
	} else {
		
		$site_title = $_POST['site_name'];
		$admin_user = $_POST['admin_user'];
		$admin_pass = $_POST['admin_pass'];
		$theme_dir = $_POST['theme_dir'];

		$db_user = $_POST['db_user'];
		$db_pass = $_POST['db_pass'];
		$db_host = $_POST['db_host'];
		$db_name = $_POST['db_name'];
		$db_prefix = $_POST['db_prefix'];

		$site_base = $_POST['site_base'];
		$site_path = $_POST['site_path'];
		$media_dir = $_POST['media_dir'];

		if (file_exists("config.php")) {
			echo "<p>A configuration already exists, aborting installation!</p>";
			exit();
		}

		$file = fopen("config.php", "w");
		if ($file === false) {
			echo "<p>Couldn't write configuration. Check your permissions!</p>";
			exit();
		}

		fwrite($file, "<?php\n");
		fwrite($file, "define(\"SITE_PATH\", \"".$site_path."\");\n");
		fwrite($file, "define(\"SITE_BASE\", \"".$site_base."\");\n\n");
		fwrite($file, "define(\"CMS_PATH\", SITE_PATH.\"cms/\");\n");
		fwrite($file, "define(\"CMS_BASE\", SITE_BASE.\"cms/\");\n\n");
		fwrite($file, "define(\"MEDIA_PATH\", SITE_PATH.\"".$media_dir."\");\n");
		fwrite($file, "define(\"MEDIA_BASE\", SITE_BASE.\"".$media_dir."\");\n\n");
		fwrite($file, "define(\"DB_HOST\", \"".$db_host."\");\n");
		fwrite($file, "define(\"DB_NAME\", \"".$db_name."\");\n");
		fwrite($file, "define(\"DB_USER\", \"".$db_user."\");\n");
		fwrite($file, "define(\"DB_PASS\", \"".$db_pass."\");\n");
		fwrite($file, "define(\"DB_PREFIX\", \"".$db_prefix."\");\n\n");
		fwrite($file, "define(\"THEME_DIR\", \"".$theme_dir."\");\n");
		fwrite($file, "?>\n");
		fclose($file);

		
		require_once($site_path."/common.php");

		// Some sane defaults, but should be handled by theme
		Settings::Set("max_page_depth", 2);
		Settings::Set("max_top_level_pages", 10);

		Settings::Set("site_start_page", 0);
		Settings::Set("site_title", $site_title);
	
		// FIXME: Let user specify credentials for the admin account
		$res = DB::Query("SELECT id FROM ".DB_PREFIX."users WHERE username='admin'");
		if (count($res) == 0) {
			$admin_vo = new DaoUser();
			$admin_vo->username = $admin_user;
			$admin_vo->password = md5($admin_pass);
			$admin_vo->fullname = "Administrator";
			$admin_vo->full_access = 1;
			DB::Insert(DB_PREFIX."users", $admin_vo);
		}

		echo "<img src=\"cms/pics/icons_64/settings.png\" style=\"margin: 10px;\"/><br/>".
		"Installation completed successfully";
	}
} else {
	echo "<img src=\"cms/pics/icons_64/warning.png\" style=\"margin: 10px;\"/><br/>".
	"Installation has already been completed";
}

?>
