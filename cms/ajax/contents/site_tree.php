<?php
require_once("../include.php");

if (isset($_GET['pid']))
	$pid = (int)$_GET['pid'];
else
	$pid = 0;

$start_page = Settings::Get("site_start_page");

$res_user = DB::Query("SELECT * FROM users WHERE id=".$_SESSION['user_id']);
$user = DB::Obj($res_user, "DaoUser");

function check_expand($page, $selected_id)
{
	$expand = false;

	if ($page->id == $selected_id) {
		return true;
	}

	$children = Theme::GetPageChildren($page->id);
	foreach ($children as $child) {
		if (check_expand($child, $selected_id))
			$expand = true;
	}

	return $expand;
}

function print_leafs($parent_id, $selected_id)
{
	global $start_page;

	if ($parent_id === NULL)
		$parent_str = "IS NULL";
	else
		$parent_str = "=".$parent_id;

	$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE parent_id ".$parent_str." ORDER BY sort");
	$num = DB::NumRows($res);

	if ($num > 0)
		echo "<ul>";

	while ($page = DB::Obj($res, "DaoPage")) {
		if ($selected_id == $page->id)
			$sel_str = "Selected";
		else
			$sel_str = "";

		if ($page->published == 0)
			$pub_class_str = "NotPublished";
		else
			$pub_class_str = "";

		if ($page->id == $start_page)
			$start_str = "<img src=\"pics/icons_16/home_no_circle.png\" ".
			"alt=\"This is the start page\" title=\"This is the start page\"/>";
		else
			$start_str = "";

		if ($page->in_menu == 1)
			$in_menu_str = "<img src=\"pics/icons_16/site_no_circle.png\" ".
			"alt=\"Visible in menu\" title=\"Visible in menu\"/>";
		else
			$in_menu_str = "";

		if ($page->published == 1)
			$pub_str = "<img src=\"pics/icons_16/visible_no_circle.png\" ".
			"alt=\"Published\" title=\"Published\"/>";
		else
			$pub_str = "";

		echo "<li><div class=\"Page ".$sel_str." ".$pub_class_str."\" ".
		"onclick=\"LoadSiteTree(".$page->id."); LoadToolbox(".$page->id.");\">".$page->title." ".
		"<span class=\"Attributes\"> ".$in_menu_str." ".$pub_str." ".$start_str."</span></div>";
		if (check_expand($page, $selected_id))
			print_leafs($page->id, $selected_id);
		echo "</li>";
	}

	if ($num > 0)
		echo "</ul>";
}

if ($pid == 0)
	$sel_str = "Selected";
else
	$sel_str = "";

if ($user->full_access == 1) {
	$site_title = Settings::Get("site_title");
} else {
	$res_page = DB::Query("SELECT * FROM pages WHERE id=".$user->start_page_id);
	$page = DB::Obj($res_page, "DaoPage");
	$site_title = $page->title;
}

echo "<ul><li><div class=\"Page ".$sel_str."\" onclick=\"LoadSiteTree(0); LoadToolbox(0);\">".$site_title."</div>";
if ($user->full_access == 1)
	print_leafs(NULL, $pid);
else
	print_leafs($user->start_page_id, $pid);
echo "</li></ul>";
?>
