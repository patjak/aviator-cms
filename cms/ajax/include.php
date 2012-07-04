<?php

$SECURE = TRUE;
$DEBUG = TRUE;

set_include_path(get_include_path().":../../");
require_once("common.php");
require_once(CMS_PATH."settings.php");

// Catch all output and create and return an JSON object
Ajax::Start();

function AjaxShutdown()
{
	$last_error = error_get_last();
	if (count($last_error) > 0) {
		Ajax::SetStatus(AJAX_STATUS_ERROR);
		if (Ajax::$show_errors) {
			echo "<p>File: ".$last_error['file']."<br/>".
			"Line: ".$last_error['line']."</p>".
			"<p>".$last_error['message']."</p>";
		}
	} else if (Ajax::GetStatus() == AJAX_STATUS_UNSET) {
		Ajax::SetStatus(AJAX_STATUS_SUCCESS);
	}

	echo Ajax::Stop();
}

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

?>
