<?php
require_once("../include.php");

$filter = $_GET['filter'];
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

$user = User::Get();
$user_id_str = "AND user_id=".$user->id;

$res = DB::Query("SELECT id FROM images WHERE name LIKE '%".$filter."%' ".$cat_str." ".$user_id_str);
$num_images = count($res);
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
foreach ($res_cat as $row_cat) {
	$res_cat_used = DB::Query("SELECT id FROM images WHERE category_id=".$row_cat['id']." ".$user_id_str);
	if (count($res_cat_used) == 0)
		continue;

	if ($row_cat['id'] == $category_id)
		$sel_str = "selected";
	else
		$sel_str = "";

	echo "<option value=\"".$row_cat['id']."\" ".$sel_str.">".$row_cat['name']."</option>";
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
if ($page == 0)
	echo "<td onclick=\"UpdateImageRefs(".$image_ref_id.", null, 0, 0, 0, 0);\"><img src=\"".CMS_BASE."pics/icons/image.png\"/><div class=\"Name\">No image</div></td>";

// Make room for the empty image
if ($page == 0) {
	$images_per_page--;
	$i = 2;
} else {
	$offset--;
	$i = 1;
}

$res = DB::Query("SELECT id FROM images WHERE name LIKE '%".$filter."%' ".$cat_str." ".$user_id_str." ORDER BY name LIMIT ".$offset.",".$images_per_page);
foreach ($res as $row) {
	$image_id = $row['id'];
	$image = new Image($image_id);
	$image->SetMaxWidth(116);
	$image->SetMaxHeight(116);
	$image->SetMinWidth(116);
	$image->SetMinHeight(116);

	echo "<td><img onclick=\"UpdateImageRefs(".$image_ref_id.", ".$image_id.", ".$max_width.", ".$max_height.", ".$min_width.", ".$min_height.");\" src=\"".$image->GetUrl()."\"/>".
	"<div class=\"Name\" onclick=\"ShowImageInfo(".$image_id.");\">".$image->GetName()."</div></td>";

	if (!($i % 4))
		echo "</tr><tr>";
	$i++;
}
?>
</tr></table>
