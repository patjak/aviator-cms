<?php
require_once("../include.php");

// Parent id
if (isset($_POST['parent_id']))
	$parent_id = (int)$_POST['parent_id'];
else
	$parent_id = 0;

if ($parent_id == 0)
	$parent_id = NULL;

// Layout id
if (isset($_POST['layout_id']))
	$layout_id = (int)$_POST['layout_id'];
else
	$layout_id = 0;

if (isset($_POST['title']))
	$title = $_POST['title'];
else
	$title = "";

if (isset($_POST['description']))
	$description = $_POST['description'];
else
	$description = "";

if (isset($_POST['published']))
	$published = 1;
else
	$published = 0;

if (isset($_POST['in_menu']))
	$in_menu = 1;
else
	$in_menu = 0;

if (isset($_POST['landing_page']))
	$landing_page = 1;
else
	$landing_page = 0;

if (isset($_POST['page_type']) && $_POST['page_type'] != 0)
	$page_type = (int)$_POST['page_type'];
else
	$page_type = NULL;

if (isset($_POST['page_style']) && $_POST['page_style'] != 0)
	$page_style = (int)$_POST['page_style'];
else
	$page_style = NULL;

// Validate input
if ($title == "") {
	Ajax::SetStatus(AJAX_STATUS_WARNING);
	echo "<p>The title must not be empty</p>";
	exit();
}

$res = DB::Query("SELECT id FROM ".DB_PREFIX."pages WHERE parent_id IS NULL");
$num_top_pages = count($res);
$max_top_level_pages = Settings::Get("max_top_level_pages");

if ($max_top_level_pages > 0 && $parent_id == NULL && $num_top_pages >= $max_top_level_pages) {
	Ajax::SetStatus(AJAX_STATUS_WARNING);
	echo "<p>You are not allowed to have more top level pages</p>";
	exit();
}

$max_page_depth = Settings::Get("max_page_depth");

if (Pages::GetDepth($parent_id) >= ($max_page_depth - 1)) {
	Ajax::SetStatus(AJAX_STATUS_WARNING);
	echo "<p>You are not allowed to have pages nested this deep</p>";
	exit();
}

$parent = Pages::Get($parent_id);
if ($parent != false && $parent->allow_subpage == 0) {
	Ajax::SetStatus(AJAX_STATUS_WARNING);
	echo "<p>This page cannot have sub pages</p>";
	exit();
}

$page = new DaoPage();
$page->title = $title;
$page->description = $description;
$page->parent_id = $parent_id;
$page->layout_id = $layout_id;
$page->published = $published;
$page->in_menu = $in_menu;
$page->landing_page = $landing_page;
$page->allow_move = 1;
$page->allow_edit = 1;
$page->allow_subpage = 1;
$page->allow_delete = 1;
$page->allow_change_style = 1;
$page->type_id = $page_type;
$page->style_id = $page_style;
DB::Insert($page);

$image_ref = new DaoImageRef();
DB::Insert($image_ref);

$page->sort = $page->id;
$page->image_ref_id = $image_ref->id;
DB::Update($page);

?>
