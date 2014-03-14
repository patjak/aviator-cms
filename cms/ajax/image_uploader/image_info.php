<?php
require_once("../include.php");
$image_id = (int)$_POST['image_id'];

$res = DB::Query("SELECT * FROM images WHERE id=:id", array("id" => $image_id));
$image_vo = DB::RowToObj("DaoImage", $res[0]);

$res = DB::Query("SELECT * FROM image_categories WHERE id=:id", array("id" => $image_vo->category_id));
if (count($res) > 0)
	$cat_name = $res[0]['name']; // FIXME: We're missing a DAO here
else
	$cat_name = "- None -";


?>
<h2>Image information</h2>
<table>
<td>Name</td><td><?php echo $image_vo->name;?></td></tr>
<td>Width</td><td><?php echo $image_vo->width;?>px</td></tr>
<td>Height</td><td><?php echo $image_vo->height;?>px</td></tr>
<td>Category</td><td><?php echo $cat_name;?></td></tr>
</table>
<p>Used on the following pages</p>
<ul>
<?php
$res = DB::Query("SELECT * FROM image_refs WHERE image_id=:image_id", array("image_id" => $image_id));
foreach ($res as $row) {
	$ref_vo = DB::RowToObj("DaoImageCache", $row);
	if ($ref_vo->content_id > 0)
		$content_vo = DB::ObjByID("DaoContent", $ref_vo->content_id);
	else
		continue;
	$page_vo = DB::ObjByID("DaoPage", $content_vo->page_id);
	echo "<li><a href=\"".CMS_BASE."?page=".PAGE_CONTENTS_EDIT."&pid=".$page_vo->id."\">".$page_vo->title."</a></li>";
}
?>
</ul>
<button onclick="DialogHide();">Close</button>
