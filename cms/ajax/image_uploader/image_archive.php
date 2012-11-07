<?php
require_once("../include.php");

$filter = mysql_real_escape_string($_GET['filter']);
$category_id = (int)$_GET['category_id'];
$page = (int)$_GET['page'];
$image_ref_id = (int)$_GET['image_ref_id'];

$max_width = (int)$_GET['max_width'];
$max_height = (int)$_GET['max_height'];
$min_width = (int)$_GET['min_width'];
$min_height = (int)$_GET['min_height'];

$images_per_page = 8;
$offset = $page * $images_per_page;

if ($category_id > 0)
	$cat_str = "AND category_id=".$category_id;
else
	$cat_str = "";

$res = DB::Query("SELECT id FROM images WHERE name LIKE '%".$filter."%' ".$cat_str);
$num_images = DB::NumRows($res);
$num_pages = ceil($num_images / $images_per_page);

?>
<div class="ImageChooserNav">
<form onsubmit="<?php

echo "UpdateImageArchive($('#image_chooser_filter').val(), $('#image_chooser_category_id').val(), 0, ".$image_ref_id.", ".
$max_width.", ".$max_height.", ".$min_width.", ".$min_height.");";
?>; return false;">
<select id="image_chooser_category_id">
<option value="0">- Category -</option>
<?php
$res_cat = DB::Query("SELECT id, name FROM ".DB_PREFIX."image_categories ORDER BY name ASC");
while ($row_cat = DB::Row($res_cat)) {
	if ($row_cat[0] == $category_id)
		$sel_str = "selected";
	else
		$sel_str = "";

	echo "<option value=\"".$row_cat[0]."\" ".$sel_str.">".$row_cat[1]."</option>";
}
?>
</select> <input type="text" id="image_chooser_filter" value="<?php echo $filter;?>" style="margin: 0px; width: 200px;"/> 
<button type="submit" style="margin: 0px;">Filter</button>
<span class="Button" <?php
if ($page > 0) {
	echo "style=\"float: left;\" onclick=\"UpdateImageArchive('".$filter."', ".$category_id.", ".($page-1).", ".$image_ref_id.", ".
	$max_width.", ".$max_height.", ".$min_width.", ".$min_height.");\"";
} else {
	echo "style=\"opacity: 0.25; cursor: default; float: left;\"";
}
?>><img src="pics/icons_32/arrow_left.png"/></span>
<span class="Button" <?php
if (($page + 1) < $num_pages) {
	echo "style=\"float: right;\" onclick=\"UpdateImageArchive('".$filter."', ".$category_id.", ".($page+1).", ".$image_ref_id.", ".
	$max_width.", ".$max_height.", ".$min_width.", ".$min_height.");\"";
} else {
	echo "style=\"opacity: 0.25; cursor: default; float: right;\"";
}
?>><img src="pics/icons_32/arrow_right.png"/></span>
</form>
</div>
<table class="ImageChooser"><tr>
<?php
$i = 1;
$res = DB::Query("SELECT id FROM images WHERE name LIKE '%".$filter."%' ".$cat_str." ORDER BY name LIMIT ".$offset.",".$images_per_page);
while ($row = DB::Row($res)) {
	$image_id = $row[0];
	$image = new Image($image_id);
	$image->SetMaxWidth(116);
	$image->SetMaxHeight(116);
	$image->SetMinWidth(116);
	$image->SetMinHeight(116);

	echo "<td onclick=\"UpdateImageRefs(".$image_ref_id.", ".$image_id.", ".$max_width.", ".$max_height.", ".$min_width.", ".
	$min_height.");\">".$image->GetImgTag()."<div class=\"Name\">".$image->GetName()."</div></td>";

	if (!($i % 4))
		echo "</tr><tr>";
	$i++;
}
?>
</tr></table>
