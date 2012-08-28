<?php
$SECURE = true; // Mark this a valid entry point
require_once("common.php");
require_once("themes/".THEME_DIR."/config.php");

$theme_path = Theme::GetPath();
$theme_base = Theme::GetBase();

require_once($theme_path."theme.php");

?>
