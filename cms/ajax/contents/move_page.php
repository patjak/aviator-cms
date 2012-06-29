<?php

require_once("../include.php");

if (isset($_GET['pid']))
	$pid = (int)$_GET['pid'];
else
	$pid = 0;

if (isset($_GET['up']))
	$up = true;
else
	$up = false;

if (isset($_GET['down']))
	$down = true;
else
	$down = false;

if (isset($_GET['top']))
	$top = true;
else
	$top = false;

if (isset($_GET['bottom']))
	$bottom = true;
else
	$bottom = false;

$res = DB::Query("SELECT * FROM pages WHERE id=".$pid);
$page = DB::Obj($res, "DaoPage");

function MoveUp(&$page)
{
	if ($page->parent_id == NULL)
		$parent_str = "IS NULL";
	else
		$parent_str = "=".$page->parent_id;

	$res = DB::Query("SELECT * FROM pages WHERE sort<".$page->sort." AND parent_id ".$parent_str." ORDER BY sort DESC");
	if ($prev_page = DB::Obj($res)) {
		$sort = $prev_page->sort;
		$prev_page->sort = $page->sort;
		$page->sort = $sort;
		DB::Update(DB_PREFIX."pages", $page);
		DB::Update(DB_PREFIX."pages", $prev_page);
		return true;
	} else {
		return false;
	}
}

function MoveDown(&$page)
{
	if ($page->parent_id == NULL)
		$parent_str = "IS NULL";
	else
		$parent_str = "=".$page->parent_id;

	$res = DB::Query("SELECT * FROM pages WHERE sort>".$page->sort." AND parent_id ".$parent_str." ORDER BY sort");
	if ($next_page = DB::Obj($res)) {
		$sort = $next_page->sort;
		$next_page->sort = $page->sort;
		$page->sort = $sort;
		DB::Update(DB_PREFIX."pages", $page);
		DB::Update(DB_PREFIX."pages", $next_page);
		return true;
	} else {
		return false;
	}
}

if ($up) {
	MoveUp($page);
}

if ($top) {
	while (MoveUp($page)) { }
}

if ($down) {
	MoveDown($page);
}

if ($bottom) {
	while (MoveDown($page)) { }
}

?>
