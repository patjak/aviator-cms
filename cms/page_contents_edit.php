<?php
require_once("secure.php");
$page_id = (int)$_GET['pid'];
$section_id = (int)$_GET['section_id'];

$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE id=".$page_id);
$page = DB::RowToObj("DaoPage", $res[0]);
?>

<div class="Box" style="float: right; width: 200px;">
<h2><img src="pics/icons_32/box.png"/>Add contents</h2>
<div id="ContentsToolbox">
<div style="margin-bottom: 20px;">
<div class="Heading" style="text-align: center;">
<?php
if (strlen($page->title) > 18)
	$title_short = substr($page->title, 0, 16)."...";
else
	$title_short = $page->title;
?>
<a href="<?php echo SITE_BASE."?page_id=".$page->id;?>" target="_blank"><?php echo $title_short;?></a>
</div>
<?php
echo HtmlLayout($page->id, $page->layout_id, 120, 100, 4, true, $section_id);
?>
<div class="Heading"></div>
</div>
<?php
// FIXME: Need a section_id to content_width_XXX here

$disp = array(); // Keep track of the ones we've shown already
$num_found = 0;
$categories = ContentCore::GetCategories();
ksort($categories, SORT_STRING);

echo "<div class=\"Accordion\">\n";
foreach ($categories as $name => $contents) {
	$contents_str = "";

	ksort($contents);
	foreach ($contents as $content) {
		$num_found++;

		if (!($section_id & $content->GetAllowedSections()))
			continue;

		$plugin_id = $content->plugin->GetId();
		$content_id = $content->GetId();

		if (!isset($disp[$plugin_id]))
			$disp[$plugin_id] = array();

		// Mark as shown
		$disp[$plugin_id][$content_id] = true;

		$contents_str .= "<div class=\"Button\" ".
		"onclick=\"InsertContent(".$page->id.", ".$section_id.", ".$plugin_id.", ".$content_id.");\">".
		"<img src=\"".$content->GetIcon32()."\"/> ".$content->GetTitle()."</div>";
	}

	// The category might be empty so only display it if we found something
	if ($contents_str != "") {
		echo "<div class=\"AccordionHeading\">".$name."</div>".
		"<div class=\"AccordionSection\">".$contents_str."</div>\n";
	}
}

$uncategorized = ContentCore::GetRegistered(CONTENT_WIDTH_ALL);
if (count($uncategorized) > $num_found) {

	echo "<div class=\"AccordionHeading\">Uncategorized</div>".
	"<div class=\"AccordionSection\">";

	foreach ($uncategorized as $content) {
		if (!($section_id & $content->GetAllowedSections()))
			continue;

		$plugin_id = $content->plugin->GetId();
		$content_id = $content->GetId();

		// Don't show already displayed content plugins
		if (isset($disp[$plugin_id]) || isset($disp[$plugin_id][$content_id]))
			continue;

		echo "<div class=\"Button\" ".
		"onclick=\"InsertContent(".$page->id.", ".$section_id.", ".
		"".$content->plugin->GetId().", ".$content->GetId().");\">".
		"<img src=\"".$content->GetIcon32()."\"/> ".$content->GetTitle()."</div>";
	}

	echo "</div><!--AccordionSection-->";
}
?>
</div><!--Accordion-->
</div><!--ContentsToolbox-->
</div>

<div id="PageContents">
</div><!--PageContents-->
