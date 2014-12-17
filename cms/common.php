<?php
require_once("secure.php");

if (isset($DEBUG) && $DEBUG) {
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
}

require_once("defines.php");

// Try to find the config file
$config_path = "../config.php";
for ($depth = 0; $depth < 5; $depth++) {
	if (file_exists($config_path))
		break;
	$config_path = "../".$config_path;
}

if ($depth >= 5) {
	echo "Fatal error: Cannot find config file!";
	exit();
}

require_once($config_path);
// Sanity check the config
if (!defined("CMS_PATH") || !defined("CMS_BASE") ||
    !defined("SITE_PATH") || !defined("SITE_BASE")) {
	echo "Fatal error: Invalid config file!";
	exit();
}

require_once("lib/db.php");
DB::Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
require_once("lib/url.php");
require_once("lib/components.php");
require_once("lib/pages.php");
require_once("lib/settings.php");
require_once("lib/image_uploader.php");
require_once("lib/link_picker.php");
require_once("lib/ajax_return.php");
require_once("lib/layout.php");
require_once("lib/permalink.php");
require_once(SITE_PATH."api/context.php");
require_once(SITE_PATH."api/plugins.php");
require_once(SITE_PATH."api/dashboard.php");
require_once(SITE_PATH."api/contents.php");
require_once(SITE_PATH."api/images.php");
require_once(SITE_PATH."api/pages.php");
require_once(SITE_PATH."api/modules.php");
require_once(SITE_PATH."api/themes.php");
require_once(SITE_PATH."api/layouts.php");
require_once(SITE_PATH."api/styles.php");
require_once(SITE_PATH."api/page_types.php");

// Data access objects
require_once("lib/dao.php");
require_once("dao/page.php");
require_once("dao/layout.php");
require_once("dao/theme.php");
require_once("dao/contents.php");
require_once("dao/images.php");
require_once("dao/links.php");
require_once("dao/users.php");
require_once("dao/resources.php");
require_once("dao/permissions.php");
require_once("dao/access_logs.php");
require_once("dao/strings.php");

// Depends on dao/users.php
require_once("lib/user.php");

// Initialize API
Pages::Init();

// Check for available themes 
$dir = getcwd() ."/";
$dir .= "../themes";
$dir = SITE_PATH . "themes";
$dir_res = opendir($dir);
while ($dir_name = readdir($dir_res)) {
	if (is_dir($dir."/".$dir_name) && $dir_name != "." && $dir_name != "..") {
		$res = DB::Query("SELECT id FROM ".DB_PREFIX."themes WHERE name=:name", array("name" => $dir_name));

		// Add theme to database if it doesn't exist
		if (count($res) == 0) {
			DB::Query("INSERT INTO ".DB_PREFIX."themes (name) VALUES('".$dir_name."')");
			$theme_id = DB::InsertID();
		} else {
			$row = $res[0];
			$theme_id = $row["id"];
		}

		// FIXME: For now we just store the theme id in settings that matches the THEME_DIR
		if ($dir_name."/" == THEME_DIR)
			Settings::Set("theme_id", $theme_id);
	}
}
closedir($dir_res);

// Load theme configuration (needed for layouts)
require_once(SITE_PATH."themes/".THEME_DIR."/config.php");

// Load plugins
PluginCore::FindAndLoadAll();

?>
