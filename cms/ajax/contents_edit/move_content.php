<?php

require_once("../include.php");

if (isset($_GET['content_id']))
	$content_id = (int)$_GET['content_id'];
else
	$content_id = 0;

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

$res = DB::Query("SELECT * FROM contents WHERE id=".$content_id);
$content = DB::RowToObj("DaoContent", $res[0]);

function MoveUp(&$content)
{
	$res = DB::Query("SELECT * FROM ".DB_PREFIX."contents WHERE sort<".$content->sort." AND ".
	"page_id=".$content->page_id." AND section_id=".$content->section_id." ORDER BY sort DESC");
	if (count($res) > 0) {
		$prev_content = DB::RowToObj("DaoContent", $res[0]);
		$sort = $prev_content->sort;
		$prev_content->sort = $content->sort;
		$content->sort = $sort;
		DB::Update($content);
		DB::Update($prev_content);
		return true;
	} else {
		return false;
	}
}

function MoveDown(&$content)
{
	$res = DB::Query("SELECT * FROM ".DB_PREFIX."contents WHERE sort>".$content->sort." AND ".
	"page_id=".$content->page_id." AND section_id=".$content->section_id." ORDER BY sort");
	if (count($res) > 0) {
		$next_content = DB::RowToObj("DaoContent", $res[0]);
		$sort = $next_content->sort;
		$next_content->sort = $content->sort;
		$content->sort = $sort;
		DB::Update($content);
		DB::Update($next_content);
		return true;
	} else {
		return false;
	}
}

if ($up) {
	MoveUp($content);
}

if ($top) {
	while (MoveUp($content)) { }
}

if ($down) {
	MoveDown($content);
}

if ($bottom) {
	while (MoveDown($content)) { }
}

?>
