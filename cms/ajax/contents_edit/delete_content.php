<?php
require_once("../include.php");

$plugin_id = (int)$_GET['plugin_id'];
$internal_id = (int)$_GET['internal_id'];
$content_id = (int)$_GET['content_id'];

$content = ContentCore::GetByPluginAndInternal($plugin_id, $internal_id);
$content->Delete($content_id);

DB::Query("DELETE FROM ".DB_PREFIX."contents WHERE id=".$content_id);
?>
