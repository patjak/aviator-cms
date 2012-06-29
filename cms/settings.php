<?php

// Preload commonly user site settings

Settings::Set("enable_page_rules", 1);
// Settings::Set("enable_page_rules", 0);
Settings::Set("max_page_depth", 3);
Settings::Set("max_top_level_pages", 5);
Settings::Set("php_memory_limit", 128);

$val = Settings::Get("enable_page_rules");
if ($val == 1)
	$val = true;
else
	$val = false;

define("PAGE_RULES_ENABLED", $val);

?>
