<?php
$SECURE = true;

set_include_path(get_include_path().":../../");
require_once("cms/common.php");

// Try to figure out what relative path we're in, we solve this by searching for the '/plugins/' directory
$plugin_dir = substr(dirname($_SERVER['PHP_SELF']), strpos($_SERVER['PHP_SELF'], "/plugins/") + strlen("/plugins/"));
Context::SetDirectory($plugin_dir);

$res = DB::Query("SELECT id FROM ".DB_PREFIX."plugins WHERE directory='".$plugin_dir."'");
$plugin = PluginAPI::GetById($res[0]['id']);
Context::SetPlugin($plugin);

require_once("../../secure.php");
require_once("../../config.php");
require_once("../../common.php");

?>
