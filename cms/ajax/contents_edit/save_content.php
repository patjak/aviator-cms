<?php
require_once("../include.php");

$plugin_id = (int)$_GET['plugin_id'];
$internal_id = (int)$_GET['internal_id'];
$content_id = (int)$_GET['content_id'];
$content_name = $_POST['content_name'];

$res = DB::Query("SELECT * FROM contents WHERE id=".$content_id);
$content_vo = DB::Obj($res, "DaoContent");
$content_vo->name = $content_name;
DB::Update(DB_PREFIX."contents", $content_vo);

$content = ContentCore::GetByPluginAndInternal($plugin_id, $internal_id);

$content->Save($content_id);
