<?php
require_once("../include.php");

if (isset($_POST['pid'])) {
	$pid = (int)$_POST['pid'];
} else {
	Ajax::SetStatus(AJAX_STATUS_ERROR);
	exit();
}

if (isset($_POST['parent_id']))
	$parent_id = (int)$_POST['parent_id'];
else
	$parent_id = 0;

if ($parent_id == 0)
	$parent_id = NULL;

if (isset($_POST['title']))
	$title = mysql_real_escape_string($_POST['title']);
else
	$title = "";

if (isset($_POST['description']))
	$description = mysql_real_escape_string($_POST['description']);
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

if (isset($_POST['allow_edit']))
	$allow_edit = 1;
else
	$allow_edit = 0;

if (isset($_POST['allow_move']))
	$allow_move = 1;
else
	$allow_move = 0;

if (isset($_POST['allow_delete']))
	$allow_delete = 1;
else
	$allow_delete = 0;

if (isset($_POST['allow_subpage']))
	$allow_subpage = 1;
else
	$allow_subpage = 0;

if (isset($_POST['allow_change_style']))
	$allow_change_style = 1;
else
	$allow_change_style = 0;

if (isset($_POST['page_style']) && $_POST['page_style'] > 0)
	$page_style = (int)$_POST['page_style'];
else
	$page_style = NULL;

// Validate input
if ($title == "") {
	Ajax::SetStatus(AJAX_STATUS_WARNING);
	echo "<p>The title must not be empty</p>";
	exit();
}

// Check move operations
if ($pid == $parent_id) {
	Ajax::SetStatus(AJAX_STATUS_WARNING);
	echo "<p>Cannot set parent page to the page you're editing</p>";
	exit();
}

if (Theme::IsParent($pid, $parent_id)) {
	Ajax::SetStatus(AJAX_STATUS_WARNING);
	echo "<p>Cannot move a page into one of it's sub pages</p>";
	exit();
}

// Fetch the actual page we're editing
$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE id=".$pid);
$page = DB::Obj($res, "DaoPage");

// More validation
$res = DB::Query("SELECT id FROM ".DB_PREFIX."pages WHERE parent_id IS NULL");
$num_top_pages = DB::NumRows($res);
$max_top_level_pages = Settings::Get("max_top_level_pages");

if ($page->allow_move == 1 && $max_top_level_pages > 0 && $parent_id == NULL &&
    $page->parent_id != NULL && $num_top_pages >= $max_top_level_pages) {
	Ajax::SetStatus(AJAX_STATUS_WARNING);
	echo "<p>You are not allowed to have more top level pages</p>";
	exit();
}


$page->title = $title;
$page->description = $description;
$page->published = $published;
$page->in_menu = $in_menu;

if (!PAGE_RULES_ENABLED || $page->allow_move == 1)
	$page->parent_id = $parent_id;

if (!PAGE_RULES_ENABLED || $page->allow_change_style == 1)
	$page->style_id = $page_style;

if (!PAGE_RULES_ENABLED) {
	$page->allow_move = $allow_move;
	$page->allow_edit = $allow_edit;
	$page->allow_subpage = $allow_subpage;
	$page->allow_delete = $allow_delete;
	$page->allow_change_style = $allow_change_style;
}

DB::Update(DB_PREFIX."pages", $page);

?>
