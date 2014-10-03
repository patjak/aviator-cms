<?php
$DEBUG = true;

if (isset($DEBUG) && $DEBUG) {
	ini_set("display_errors", 1);
	error_reporting(E_ALL & ~E_DEPRECATED);
}

if (!file_exists(SITE_PATH."config.php")) {
	echo "<p>No config.php file found</p>".
	"<p>Make sure you've properly installed and configured Aviator</p>";
	exit();
}

require_once("config.php");
require_once(CMS_PATH."lib/db.php");
DB::Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Frontend might need to know if a CMS user is logged in
session_start();
require_once(CMS_PATH."lib/dao.php");
require_once(CMS_PATH."dao/users.php");
require_once(CMS_PATH."lib/user.php");
require_once(CMS_PATH."lib/permalink.php");

require_once(CMS_PATH."lib/settings.php");
require_once(SITE_PATH."api/context.php");
require_once(SITE_PATH."api/plugins.php");
require_once(SITE_PATH."api/contents.php");
require_once(SITE_PATH."api/images.php");
require_once(SITE_PATH."api/pages.php");
require_once(SITE_PATH."api/themes.php");
require_once(SITE_PATH."api/dashboard.php");
require_once(SITE_PATH."api/modules.php");
require_once(SITE_PATH."api/layouts.php");
require_once(SITE_PATH."api/styles.php");
require_once(SITE_PATH."api/page_types.php");
require_once(SITE_PATH."api/components.php");

// Data access objects
require_once(CMS_PATH."dao/page.php");
require_once(CMS_PATH."dao/layout.php");
require_once(CMS_PATH."dao/theme.php");
require_once(CMS_PATH."dao/contents.php");
require_once(CMS_PATH."dao/images.php");
require_once(CMS_PATH."dao/links.php");
require_once(CMS_PATH."dao/users.php");
require_once(CMS_PATH."dao/strings.php"); // Also DaoBlob

// Initialize API
Pages::Init();

// Load plugins
PluginCore::FindAndLoadAll();

?>
