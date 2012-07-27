<?php
require_once("../include.php");

// This file generates contents for the contents toolbox

if (isset($_GET['pid']))
	$pid = (int)$_GET['pid'];
else
	$pid = 0;

// Page might have been deleted
$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE id=".$pid);
if (DB::NumRows($res) == 0)
	$pid = 0;

if ($pid > 0) {
	$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE id=".$pid);
	$page = DB::Obj($res, "DaoPage");

	echo "<div class=\"Heading\">".
	"<img style=\"margin: 0px; margin-right: 14px; float: left;\" src=\"pics/icons_24/visible.png\"/>".
	"<a href=\"".SITE_BASE."?page_id=".$page->id."\" target=\"_blank\" style=\"line-height: 24px;\">".
	" ".$page->title."</a><div style=\"clear: both;\"></div></div>".
	"<div style=\"text-align: center; margin-bottom: 20px;\">";
	HtmlLayout($page->id, $page->layout_id, 120, 100, 4, true);

	if (!PAGE_RULES_ENABLED || $page->allow_move == 1) {
		echo "<span class=\"Button\" onclick=\"MovePageTop(".$pid.")\">".
		"<img alt=\"Move to top\" title=\"Move to top\" src=\"pics/icons_24/arrow_top.png\"/></span>".
		"<span class=\"Button\" onclick=\"MovePageUp(".$pid.")\">".
		"<img alt=\"Move up\" title=\"Move up\" src=\"pics/icons_24/arrow_up.png\"/></span>".
		"<span class=\"Button\" onclick=\"MovePageDown(".$pid.")\">".
		"<img alt=\"Move down\" title=\"Move down\" src=\"pics/icons_24/arrow_down.png\"/></span>".
		"<span class=\"Button\" onclick=\"MovePageBottom(".$pid.")\">".
		"<img alt=\"Move to bottom\" title=\"Move to bottom\" src=\"pics/icons_24/arrow_bottom.png\"/></span>";
	}
	echo "<div class=\"Heading\"></div></div>";
}

$allow_change_start_page = (int)Settings::Get("allow_change_start_page");

if ((!PAGE_RULES_ENABLED || $allow_change_start_page) && $pid == 0) {
?>
<span class="Button" onclick="ShowSelectStartPage();"><img src="pics/icons_32/home.png"/> Select start page</span><br/>
<?php
}

?>
<span class="Button" onclick="ShowNewPage(<?php echo $pid;?>);"><img src="pics/icons_32/page.png"/> New subpage</span><br/>
<?php
if ($pid > 0) {
?>
<a href="<?php echo SITE_BASE."?page_id=".$page->id;?>" target="_blank"><img src="pics/icons_32/visible.png"/> View page</a><br/>
<span class="Button" onclick="ShowEditPage(<?php echo $pid;?>);"><img src="pics/icons_32/edit.png"/> Edit settings</span><br/>
<?php
if (!PAGE_RULES_ENABLED || $page->allow_delete) { ?>
<span class="Button" onclick="AskDeletePage(<?php echo $pid;?>);"><img src="pics/icons_32/trash.png"/> Delete page</span>
<?php
}
}
?>
</div>
