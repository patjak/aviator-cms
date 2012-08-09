<?php

define("SITE_PATH", dirname(__FILE__)."/");
define("SITE_BASE", "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME'])."/");

define("CMS_PATH", SITE_PATH."/cms/");
define("CMS_BASE", SITE_BASE."/cms/");

define("MEDIA_PATH", SITE_PATH."media/");
define("MEDIA_BASE", SITE_BASE."media/");

define("DB_HOST", "localhost");
define("DB_NAME", "website");
define("DB_USER", "website_user");
define("DB_PASS", "secret_password");
define("DB_PREFIX", "");

?>
