<?php
require_once("../include.php");

// Parent id
if (isset($_GET['pid']))
	$pid = (int)$_GET['pid'];
else
	$pid = 0;

$res = DB::Query("SELECT id FROM ".DB_PREFIX."pages WHERE parent_id=".$pid);
if (count($res)) {
	echo "<p>Cannot delete a page with subpages. Delete them first.</p>";
	Ajax::SetStatus(AJAX_STATUS_NOTICE);
	exit();
}

// Delete all the page contents by issuing PageDelete and Delete on the contents
$res = DB::Query("SELECT * FROM ".DB_PREFIX."contents WHERE page_id=".$pid);
foreach ($res as $row) {
	$content_vo = DB::RowToObj("DaoContent", $row);
	$content = ContentCore::GetByPluginAndInternal($content_vo->plugin_id, $content_vo->internal_id);
	$content->id = $content_vo->id;
	$content->PageDelete($content_vo->id);
	$content->Delete($content_vo->id);
	DB::Query("DELETE FROM ".DB_PREFIX."contents WHERE id=".$content_vo->id);
}

// Update start page if needed
$start_page = (int)Settings::Get("site_start_page");
if ($start_page == $pid)
	Settings::Set("site_start_page", "0");

// Unreference all the links pointing to this page
DB::Query("UPDATE ".DB_PREFIX."links SET internal_page_id=NULL, enabled=0 WHERE internal_page_id=".$pid);
$affected_links = DB::AffectedRows();

if ($affected_links > 0) {
	echo "<p>".$affected_links." links pointed to this page. They have been disabled.</p>";
	Ajax::SetStatus(AJAX_STATUS_NOTICE);
}

DB::Query("DELETE FROM ".DB_PREFIX."pages WHERE id=".$pid);

?>
