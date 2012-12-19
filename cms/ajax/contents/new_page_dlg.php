<?php
require_once("../include.php");

// Parent id
if (isset($_GET['parent_id']))
	$parent_id = (int)$_GET['parent_id'];
else
	$parent_id = 0;

function EchoSiteTree($parent_id, $selected, $depth_str = "-")
{
	$site_title = Settings::Get("site_title");
	if ($parent_id == 0) {
		$parent_str = "IS NULL";
		echo "<option value=\"0\">".$site_title."</option>";
	} else {
		$parent_str = "=".$parent_id;
	}

	$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE parent_id ".$parent_str);

	while ($page = DB::Obj($res, "DaoPage")) {

		if ($page->id == $selected)
			$sel_str = "selected";
		else
			$sel_str = "";

		echo "<option value=\"".$page->id."\" ".$sel_str.">".$depth_str." ".$page->title."</option>";
		EchoSiteTree($page->id, $selected, $depth_str." -");
	}
}

// We need to know the first id of layouts for initial js value
$layouts = Layout::GetAll();
$layout = reset($layouts);

?>
<form onsubmit="InsertNewPage($(this), $(this).children('select[name=parent_id]').val()); return false;">
<h2><img src="pics/icons_32/paper_new.png"/> Add new page</h2>
<input type="hidden" id="layout_id" name="layout_id" value="<?php echo $layout->id;?>"/>
<div class="Heading">Page title</div>
<input type="text" name="title" style="width: 200px;"/>
<div class="Heading">Description</div>
<input type="text" name="description" style="width: 600px;"/>
<div class="Heading">Attributes</div>
<nobr><input type="checkbox" name="published"/>Published</nobr><br/>
<nobr><input type="checkbox" name="in_menu" checked/>Visible in menu</nobr>

<div class="Heading">Parent page</div>
<select name="parent_id">
<?php EchoSiteTree(0, $parent_id);?>
</select>

<?php

if (count(ModuleCore::GetPageTypes()) > 0) {
	echo "<div class=\"Heading\">Page type</div>".
	"<select name=\"page_type\"><option value=\"0\">Normal</option>";

	foreach (ModuleCore::GetPageTypes() as $page_type) {
		echo "<option value=\"".$page_type->type_id."\">".$page_type->name."</option>";
	}

	echo "</select>";
} else {
	echo "<input type=\"hidden\" name=\"page_type\" value=\"0\"/>";
}

/* FIXME: Disable page styles for now
$res = DB::Query("SELECT * FROM page_styles");
$num = DB::NumRows($res);
if ($num > 0) {
	echo "<div class=\"Heading\">Page style</div>".
	"<select name=\"page_style\">";

	while ($page_style = DB::Obj($res, "DaoPageStyle")) {
		echo "<option value=\"".$page_style->id."\">".$page_style->name."</option>";
	}

	echo "</select>";
} else {
*/
	echo "<input type=\"hidden\" name=\"page_style\" value=\"0\"/>";
/*
}
*/
?>

<div class="Heading">Select layout</div>
<table class="ChooseLayout"><tr>
<?php
$i = 0;
$layouts = Layout::GetAll();
foreach($layouts as $layout) {
	if (!($i % 5)) // 4 layouts per row
		echo "</tr><tr>";
	if ($i == 0)
		$sel_str = "class=\"Selected\"";
	else
		$sel_str = "";

	echo "<td ".$sel_str." onclick=\"SetLayoutNewPage(".$layout->id.", $(this));\">";
	HtmlLayout(0, $layout->id, 90, 80, 4);
	echo "</td>";
	$i++;
}
?>
</tr></table>
<div class="Heading"></div>
<div style="text-align: center;">
<button type="submit"><img src="pics/icons_24/check.png"/> Add page</button> 
</div>
</form>
