<?php
require_once("secure.php");
$page_id = (int)$_GET['pid'];
$section_id = (int)$_GET['section_id'];

$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE id=".$page_id);
$page = DB::Obj($res, "DaoPage");
?>

<div class="Box" style="float: right; width: 200px;">
<h2><img src="pics/icons_32/box.png"/>Add contents</h2>
<div id="ContentsToolbox">
<div style="margin-bottom: 20px;">
<div class="Heading">
<a href="<?php echo SITE_BASE."?page_id=".$page->id;?>" target="_blank">
<img src="pics/icons_24/visible.png" style="margin-bottom: 5px; margin-right: 10px;"/> <?php echo $page->title;?></a>
</div>
<?php
echo HtmlLayout($page->id, $page->layout_id, 120, 100, 4, true, $section_id);
?>
<div class="Heading"></div>
</div>
<?php
// FIXME: Need a section_id to content_width_XXX here
foreach (ContentCore::GetRegistered(CONTENT_WIDTH_ALL) as $content) {
	if (!($section_id & $content->GetAllowedSections()))
		continue;

	echo "<p><span class=\"Button\" ".
	"onclick=\"InsertContent(".$page->id.", ".$section_id.", ".$content->plugin->GetId().", ".$content->GetId().");\">".
	"<img src=\"".$content->GetIcon32()."\"/> ".$content->GetTitle()."</span></p>";
}
?>
</div><!--ContentsToolbox-->
</div>

<div id="PageContents">
</div><!--PageContents-->
