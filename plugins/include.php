<?php

$SECURE = TRUE;
$DEBUG = TRUE;

set_include_path(get_include_path().":../../");
require_once("cms/common.php");

// Try to figure out what relative path we're in, we solve this by searching for the '/plugins/' directory
$plugin_dir = substr(dirname($_SERVER['PHP_SELF']), strpos($_SERVER['PHP_SELF'], "/plugins/") + strlen("/plugins/"));
Context::SetDirectory($plugin_dir);

$res = DB::Query("SELECT id FROM ".DB_PREFIX."plugins WHERE directory='".$plugin_dir."'");
$plugin = PluginAPI::GetById($res[0]['id']);
Context::SetPlugin($plugin);

function AjaxShutdown()
{
	$error_last = error_get_last();

	if (count($error_last) > 0) {
		Ajax::SetStatus(AJAX_STATUS_ERROR);
		// var_dump($error_last);
	} else if (Ajax::GetStatus() == AJAX_STATUS_UNSET) {
		Ajax::SetStatus(AJAX_STATUS_SUCCESS);
	}

	echo Ajax::Stop();
}

// If we're not running in plugin.php we are an ajax call
if (substr($_SERVER['PHP_SELF'], -10) != "plugin.php") {

	// Catch all output and create and return an JSON object
	Ajax::Start();

	register_shutdown_function('AjaxShutdown');

	/* If we run into an error, mark the JSON return accordingly
   	Unfortunately we cannot catch fatal errors here */
	function AjaxErrorHandler($errno, $errstr)
	{
		Ajax::SetStatus(AJAX_STATUS_ERROR);

		return false;
	}

	set_error_handler('AjaxErrorHandler');

	// Make sure the user is logged in and session is still alive
	session_start();
	if (!isset($_SESSION['user_id'])) {
		Ajax::SetStatus(AJAX_STATUS_TIMEOUT);
		exit();
	}

}

if (isset($_GET['action'])) {
	if (isset($_GET['module']) && isset($_GET['plugin'])) {
		$plugin_id = (int)$_GET['plugin'];
		$module_id = (int)$_GET['module'];

		$module = ModuleCore::GetByPluginAndInternal($plugin_id, $module_id);
		require_once(SITE_PATH."plugins/".$module->plugin->GetDirectory()."/".$module->GetCurrentView());
		exit(); // We depend on the plugin author to redirect to proper page after action
		// CONTINUE HERE
	}
}

?>
