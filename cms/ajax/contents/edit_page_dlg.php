<?php
require_once("../include.php");

if (isset($_GET['pid']))
	$pid = (int)$_GET['pid'];
else
	$pid = 0;

function EchoSiteTree($parent_id, $selected, $depth_str = "-")
{
	$site_title = Settings::Get("site_title");
	if ($parent_id == 0) {
		$parent_str = "IS NULL";
		$parent_array = array();
		echo "<option value=\"0\">".$site_title."</option>";
	} else {
		$parent_str = "=:parent_id";
		$parent_array = array("parent_id" => $parent_id);
	}

	$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE parent_id ".$parent_str." ORDER BY sort ASC", $parent_array);

	foreach ($res as $row) {
		$page = DB::RowToObj("DaoPage", $row);
		if ($page->id == $selected)
			$sel_str = "selected";
		else
			$sel_str = "";

		echo "<option value=\"".$page->id."\" ".$sel_str.">".$depth_str." ".$page->title."</option>";
		EchoSiteTree($page->id, $selected, $depth_str." -");
	}
}

$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE id=".$pid);
$page = DB::RowToObj("DaoPage", $res[0]);

if ($page->in_menu == 1)
	$in_menu_str = "checked";
else
	$in_menu_str = "";

if ($page->published == 1)
	$published_str = "checked";
else
	$published_str = "";

$landing_page_str = ($page->landing_page == 1) ? "checked" : "";

?>
<form onsubmit="UpdatePage($(this), <?php echo $page->id;?>); return false;">
<input type="hidden" name="pid" value="<?php echo $pid;?>"/>
<h2><img src="pics/icons_32/paper_new.png"/> Edit page settings</h2>
<div class="Heading">Page title</div>
<input type="text" name="title" value="<?php echo htmlentities($page->title, ENT_QUOTES, "UTF-8");?>" style="width: 200px;"/>
<div class="Heading">Description</div>
<input type="text" name="description" value="<?php echo htmlentities($page->description, ENT_QUOTES, "UTF-8");?>" style="width: 600px;"/>

<div class="Heading">Permalink</div>
<?php
$permalink_absolute_str = ($page->permalink_absolute == 1) ? "checked" : "";
$permalink_hide_str = ($page->permalink_hide_in_tree == 1) ? "checked" : "";
?>
<input type="text" name="permalink" value="<?php echo $page->permalink;?>"/> <i>(Leave empty for default)</i><br/>
Default: <?php echo $page->permalink_assigned;?><br/>
<input type="checkbox" name="permalink_absolute" <?php echo $permalink_absolute_str;?>/> Absolute permalink<br/>
<input type="checkbox" name="permalink_hide_in_tree" <?php echo $permalink_hide_str;?>/> Hide in tree<br/>

<div class="Heading">Attributes</div>
<input type="checkbox" name="published" <?php echo $published_str;?>/>Published<br/>
<input type="checkbox" name="in_menu" <?php echo $in_menu_str;?>/>Visible in menu<br/>
<input type="checkbox" name="landing_page" <?php echo $landing_page_str;?>/>Landing page

<?php
if ($page->allow_edit == 1)
	$allow_edit_str = "checked";
else
	$allow_edit_str = "";

if ($page->allow_move == 1)
	$allow_move_str = "checked";
else
	$allow_move_str = "";

if ($page->allow_delete == 1)
	$allow_delete_str = "checked";
else
	$allow_delete_str = "";

if ($page->allow_subpage == 1)
	$allow_subpage_str = "checked";
else
	$allow_subpage_str = "";

if ($page->allow_change_style == 1)
	$allow_change_style_str = "checked";
else
	$allow_change_style_str = "";

if (!PAGE_RULES_ENABLED) {

?>
<div class="Heading">Rules</div>
<input type="checkbox" name="allow_edit" <?php echo $allow_edit_str;?>/>Allow edit<br/>
<input type="checkbox" name="allow_move" <?php echo $allow_move_str;?>/>Allow move<br/>
<input type="checkbox" name="allow_delete" <?php echo $allow_delete_str;?>/>Allow delete<br/>
<input type="checkbox" name="allow_subpage" <?php echo $allow_subpage_str;?>/>Allow subpages<br/>
<input type="checkbox" name="allow_change_style" <?php echo $allow_change_style_str;?>/>Allow change of style<br/>
<?php
}

if (!PAGE_RULES_ENABLED || $page->allow_move == 1) {
?>
<div class="Heading">Parent page</div>
<select name="parent_id">
<?php EchoSiteTree(0, $page->parent_id);?>
</select>
<?php
}

if (!PAGE_RULES_ENABLED || $page->allow_change_style == 1) {

	$styles = Style::GetAll();
	if (count($styles) > 0) {
		echo "<div class=\"Heading\">Page style</div>".
		"<select name=\"page_style\">";
	
		foreach ($styles as $style) {
			if ($style->id == $page->style_id)
				$sel_str = " selected";
			else
				$sel_str = "";
	
			echo "<option value=\"".$style->id."\" ".$sel_str.">".$style->name."</option>";
		}
	
		echo "</select>";
	} else {
		echo "<input type=\"hidden\" name=\"page_style\" value=\"0\"/>";
	}
}
?>

<div class="Heading"></div>
<div style="text-align: center;">
<button type="submit">
<img src="pics/icons_24/check.png"/> Save settings</button> 
</div>
</form>
