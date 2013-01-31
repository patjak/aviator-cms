<?php
$DEBUG = true;

if (isset($DEBUG) && $DEBUG) {
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
}

require_once("config.php");
require_once(CMS_PATH."lib/db.php");
require_once(CMS_PATH."lib/settings.php");
require_once(SITE_PATH."api/context.php");
require_once(SITE_PATH."api/plugins.php");
require_once(SITE_PATH."api/contents.php");
require_once(SITE_PATH."api/images.php");
require_once(SITE_PATH."api/themes.php");
require_once(SITE_PATH."api/dashboard.php");
require_once(SITE_PATH."api/modules.php");
require_once(SITE_PATH."api/layouts.php");
require_once(SITE_PATH."api/styles.php");
require_once(SITE_PATH."api/page_types.php");

// Data access objects
require_once(CMS_PATH."dao/page.php");
require_once(CMS_PATH."dao/layout.php");
require_once(CMS_PATH."dao/theme.php");
require_once(CMS_PATH."dao/contents.php");
require_once(CMS_PATH."dao/images.php");
require_once(CMS_PATH."dao/links.php");
require_once(CMS_PATH."dao/users.php");

// Load plugins
PluginCore::FindAndLoadAll();

?>
