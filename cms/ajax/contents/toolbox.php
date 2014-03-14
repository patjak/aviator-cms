<?php
require_once("../include.php");
$user = User::Get();

// This file generates contents for the contents toolbox

if (isset($_GET['pid']))
	$pid = (int)$_GET['pid'];
else
	$pid = 0;

// Page might have been deleted
$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE id=:id", array("id" => $pid));
if (count($res) == 0)
	$pid = 0;

if ($pid > 0) {
	$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE id=:id", array("id" => $pid));
	$page = DB::RowToObj("DaoPage", $res[0]);

	if (strlen($page->title) > 18)
		$title_short = substr($page->title, 0, 16)."...";
	else
		$title_short = $page->title;

	echo "<div class=\"Heading\" style=\"text-align: center;\">".
	"<a href=\"".SITE_BASE."?page_id=".$page->id."\" target=\"_blank\" style=\"line-height: 24px;\">".
	" ".$title_short."</a></div>".
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

// Find image ref or create one if it doesn't exist
if ($pid > 0 && $user->full_access == 1) {
	$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE id=:id", array("id" => $pid));
	$page = DB::RowToObj("DaoPage", $res[0]);

	if ($page->image_ref_id == NULL) {
		$image_ref = new DaoImageRef();
		DB::Insert($image_ref);
		$page->image_ref_id = $image_ref->id;
		DB::Update($page);
	} else {
		$res = DB::Query("SELECT * FROM ".DB_PREFIX."image_refs WHERE id=".$page->image_ref_id);
		$image_ref = DB::RowToObj("DaoImageRef", $res[0]);
	}

	$img_uploader = new ImageUploader($image_ref);
	$img_uploader->SetThumbMaxWidth(180);
	$img_uploader->SetThumbMaxHeight(100);
	$img_uploader->ShowLink(false);
	echo "<span class=\"Button\" onclick=\"$('div.PageImageUploader').slideToggle(200);\">".
	"<img src=\"pics/icons_32/image.png\"/> Page image</span><br/>".
	"<div class=\"PageImageUploader\" style=\"text-align: center; display: none;\">".
	"<form onsubmit=\"SavePageImage($(this)); return false;\" method=\"POST\">".
	"<input type=\"hidden\" name=\"image_ref_id\" value=\"".$image_ref->id."\"/>";
	$img_uploader->Render();
	echo "<button type=\"submit\">Save</button> ".
	"<button onclick=\"$('div.PageImageUploader').slideToggle(200); return false;\">Cancel</button>".
	"<div class=\"Heading\"></div>".
	"</form>".
	"</div>";
}
if ($user->full_access == 1) {
?>
<span class="Button" onclick="ShowNewPage(<?php echo $pid;?>);"><img src="pics/icons_32/page.png"/> New subpage</span><br/>
<?php
}

if ($pid > 0 && $user->full_access == 1) {
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
