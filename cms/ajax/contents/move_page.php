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

$res = DB::Query("SELECT * FROM pages WHERE id=:id", array("id" => $pid));
$page = DB::RowToObj("DaoPage", $res[0]);

function MoveUp(&$page)
{
	if ($page->parent_id == NULL)
		$parent_str = "IS NULL";
	else
		$parent_str = "=".$page->parent_id;

	$res = DB::Query("SELECT * FROM pages WHERE sort<".$page->sort." AND parent_id ".$parent_str." ORDER BY sort DESC");
	if (count($res) > 0) {
		$prev_page = DB::RowToObj("DaoPage", $res[0]);
		$sort = $prev_page->sort;
		$prev_page->sort = $page->sort;
		$page->sort = $sort;
		DB::Update($page);
		DB::Update($prev_page);
		return true;
	} else {
		return false;
	}
}

function MoveDown(&$page)
{
	$query_array = array("sort" => $page->sort);

	if ($page->parent_id == NULL) {
		$parent_str = " IS NULL";
	} else {
		$parent_str = "=:parent_id";
		$query_array["parent_id"] = $page->parent_id;
	}

	$res = DB::Query("SELECT * FROM pages WHERE sort>:sort AND parent_id".$parent_str." ORDER BY sort", $query_array);
	if (count($res) > 0) {
		$next_page = DB::RowToObj("DaoPage", $res[0]);
		$sort = $next_page->sort;
		$next_page->sort = $page->sort;
		$page->sort = $sort;
		DB::Update($page);
		DB::Update($next_page);
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
