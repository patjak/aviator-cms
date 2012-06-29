<?php

define("AJAX_STATUS_SUCCESS", 0);	// Request succeeded
define("AJAX_STATUS_ERROR", 1);	// Request failed
define("AJAX_STATUS_TIMEOUT", 2);	// Session has timed out
define("AJAX_STATUS_WARNING", 3);	// Request succeded but with warnings
define("AJAX_STATUS_NOTICE", 4);	// Request succeeded but with additional information
define("AJAX_STATUS_UNSET", 5);	// Will turn into success unless an PHP error has occured

// This class declares the JSON object javascript is expecting to recieve
class AjaxReturnObject {
	public 	$status,
		$html;
}

class Ajax {
	private static $obj;
	public static $show_errors = true;

	public static function Start()
	{
		Ajax::$obj = new AjaxReturnObject();
		Ajax::$obj->status = AJAX_STATUS_UNSET; // Default to UNSET

		return ob_start();
	}

	public static function Stop()
	{
		Ajax::$obj->html = ob_get_contents();
		ob_end_clean();

		return json_encode(Ajax::$obj);
	}

	public static function SetStatus($status)
	{
		Ajax::$obj->status = $status;
	}

	public static function GetStatus()
	{
		return Ajax::$obj->status;
	}

	public static function ClearOutput()
	{
		ob_clean();
	}

	public static function ShowErrors($val)
	{
		Ajax::$show_errors = $val;
	}
}

?>
