<?php
require_once("secure.php");

if (isset($DEBUG) && $DEBUG) {
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
}

require_once("defines.php");
require_once("config.php");
require_once("lib/db.php");
require_once("lib/url.php");
require_once("lib/components.php");
require_once("lib/pages.php");
require_once("lib/user.php");
require_once("lib/settings.php");
require_once("lib/image_uploader.php");
require_once("settings.php");
require_once("lib/ajax_return.php");
require_once("lib/layout.php");
require_once(SITE_PATH."api/context.php");
require_once(SITE_PATH."api/plugins.php");
require_once(SITE_PATH."api/dashboard.php");
require_once(SITE_PATH."api/contents.php");
require_once(SITE_PATH."api/images.php");
require_once(SITE_PATH."api/modules.php");
require_once(SITE_PATH."api/themes.php");

// Data access objects
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

// Load plugins
PluginCore::FindAndLoadAll();

?>
