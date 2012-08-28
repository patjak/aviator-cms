<?php

// Preload commonly user site settings

// Here are some default settings to use until we can autodetect or let
// admin choose them in site settings
Settings::Set("max_page_depth", 3);
Settings::Set("max_top_level_pages", 5);

// Parse memory_limit setting with ini_get()
$val = ini_get("memory_limit");
$val = trim($val);
$postfix = strtolower($val[strlen($val)-1]);
$val = substr($val, 0, -1);

switch($postfix) {
case 'g':
	$val *= 1024;
	break;
case 'k':
	$val /= 1024;
	break;
case 'm':
default:
	// We assume MB
}
// Store memory_limit in MB
Settings::Set("php_memory_limit", $val);

$user_vo = User::Get();


// FIXME: This will go away when user resources and permissions are in place
if ($user_vo !== false && $user_vo->full_access != 1)
	define("PAGE_RULES_ENABLED", true);
else
	define("PAGE_RULES_ENABLED", false);

?>
