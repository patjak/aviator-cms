<?php
require_once("../include.php");

$filter = mysql_real_escape_string($_GET['filter']);
$page = (int)$_GET['page'];
$image_ref_id = (int)$_GET['image_ref_id'];

$max_width = (int)$_GET['max_width'];
$max_height = (int)$_GET['max_height'];
$min_width = (int)$_GET['min_width'];
$min_height = (int)$_GET['min_height'];

$images_per_page = 8;
$offset = $page * $images_per_page;

$res = DB::Query("SELECT id FROM images WHERE name LIKE '%".$filter."%'");
$num_images = DB::NumRows($res);
$num_pages = ceil($num_images / $images_per_page);

?>
<div class="ImageChooserNav">
<form onsubmit="<?php

echo "UpdateImageArchive($('#image_chooser_filter').val(), 0, ".$image_ref_id.", ".
$max_width.", ".$max_height.", ".$min_width.", ".$min_height.");";
?>; return false;">
<input type="text" id="image_chooser_filter" value="<?php echo $filter;?>" style="margin: 0px; width: 200px;"/> 
<button type="submit" style="margin: 0px;">Filter</button>
<span class="Button" <?php
if ($page > 0) {
	echo "style=\"float: left;\" onclick=\"UpdateImageArchive('".$filter."', ".($page-1).", ".$image_ref_id.", ".
	$max_width.", ".$max_height.", ".$min_width.", ".$min_height.");\"";
} else {
	echo "style=\"opacity: 0.25; cursor: default; float: left;\"";
}
?>><img src="pics/icons_32/arrow_left.png"/></span>
<span class="Button" <?php
if (($page + 1) < $num_pages) {
	echo "style=\"float: right;\" onclick=\"UpdateImageArchive('".$filter."', ".($page+1).", ".$image_ref_id.", ".
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
$res = DB::Query("SELECT id FROM images WHERE name LIKE '%".$filter."%' ORDER BY name LIMIT ".$offset.",".$images_per_page);
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
