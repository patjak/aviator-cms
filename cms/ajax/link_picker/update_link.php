<?php
require_once("../include.php");

$link_id = (int)$_POST['link_id'];
$res = DB::Query("SELECT * FROM ".DB_PREFIX."links WHERE id=".$link_id);
$vo = DB::Obj($res, "DaoLink");

$vo->name = $_POST['name'];
$vo->is_internal = (int)$_POST['is_internal'];
$vo->internal_page_id = (int)$_POST['internal_page_id'];
$vo->external_url = $_POST['external_url'];
$vo->in_new_window = (int)$_POST['in_new_window'];
$vo->enabled = (int)$_POST['enabled'];

$lp = new LinkPicker($vo);
$lp->RenderInner();

?>
