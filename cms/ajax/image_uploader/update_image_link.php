<?php
require_once("../include.php");

$link_id = (int)$_POST['link_id'];

$res = DB::Query("SELECT * FROM ".DB_PREFIX."links WHERE id=".$link_id);
$link_vo = DB::RowToObj("DaoLink", $res[0]);

$link_vo->enabled = (int)$_POST['enabled'];
$link_vo->in_new_window = (int)$_POST['in_new_window'];
$link_vo->is_internal = (int)$_POST['is_internal'];

// Only update the internal / external if it's selected
if ($link_vo->is_internal == 1)
	$link_vo->internal_page_id = (int)$_POST['internal_page_id'];
else
	$link_vo->external_url = $_POST['external_url']; // Escaped in DB::Update

DB::Update($link_vo);
?>
