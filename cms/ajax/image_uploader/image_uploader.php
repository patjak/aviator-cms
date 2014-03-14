<?php

require_once("../include.php");

$image_ref_id = (int)$_GET['image_ref_id'];
$max_width = (int)$_GET['max_width'];
$max_height = (int)$_GET['max_height'];
$min_width = (int)$_GET['min_width'];
$min_height = (int)$_GET['min_height'];
if (isset($_GET['show_link']))
	$show_link = (int)$_GET['show_link'];
else
	$show_link = true;

if ($show_link == 1)
	$show_link = true;
else
	$show_link = false;

$res_ref = DB::Query("SELECT * FROM ".DB_PREFIX."image_refs WHERE id=".$image_ref_id);
$image_ref = DB::RowToObj("DaoImageRef", $res_ref[0]);

if ($image_ref->link_id != NULL) {
	$res_link = DB::Query("SELECT * FROM ".DB_PREFIX."links WHERE id=".$image_ref->link_id);
	$link_vo = DB::RowToObj("DaoLink", $res_link[0]);
} else {
	$show_link = false;
}

?>
<h2>Upload or choose image</h2>
<form id="file_upload_form" target="image_upload_target" enctype="multipart/form-data" method="POST" action="ajax/image_uploader/form_submit.php">
<input type="hidden" name="image_ref_id" value="<?php echo $image_ref_id;?>"/>
<input type="hidden" name="max_width" value="<?php echo $max_width;?>"/>
<input type="hidden" name="max_height" value="<?php echo $max_height;?>"/>
<input type="hidden" name="min_width" value="<?php echo $min_width;?>"/>
<input type="hidden" name="min_height" value="<?php echo $min_height;?>"/>
<div class="Heading">Upload new image</div>
<table>
<tr><td>Name</td><td><input type="text" name="image_name"/></td></tr>
<tr><td>Description</td><td><input type="text" name="image_description"/></td></tr>
<tr><td>Category</td><td><select name="image_category_id" onchange="$('#image_category_name').fadeOut(200); if ($(this).val() == -1) {$('#image_category_name').fadeIn(200);}">
<option value="0" >- None -</option>
<option value="-1">- Add new -</option>
<?php
$res_img_cat = DB::Query("SELECT id, name FROM image_categories ORDER BY name ASC");
foreach ($res_img_cat as $row_img_cat) {
	$res_num_cat_users = DB::Query("SELECT id FROM images WHERE category_id=".$row_img_cat['id']);
	if (count($res_num_cat_users) > 0)
		echo "<option value=\"".$row_img_cat['id']."\">".$row_img_cat['name']."</option>";
}
?>
</select></td><td><input type="text" id="image_category_name" name="image_category_name" style="display: none;"/></td></tr>
<tr><td>File</td>
<td><div style="position: relative; width: 150px; margin: 0px; padding: 0px; height: 33px; overflow: hidden;">
<button style="position: absolute; margin-top: 0px; width: 150px; height: 33px;" type="button" onclick="$('#image_upload_input').click();"><img src="pics/icons_24/image.png"/> Choose file</button>
<input type="file" name="file" id="image_upload_input" style="opacity: 0.0; position: absolute; top: 0px; left: 0px; z-index: 2;"/>
</div></td>
<td><button type="button" style="width: 130px;" onclick="$(this).closest('form').submit(); LoadShow(); return false;"><img src="pics/icons_24/check.png"/> Upload</button></td></tr>
</table>
</form>

<?php if ($show_link) { ?>
<div class="Heading">Add image link</div>
<form method="POST" action="ajax/image_uploader/update_image_link.php" onsubmit="SaveImageLink(<?php echo $link_vo->id;?>); return false;">
<table>
<tr><td style="width: 200px;">Enable link</td><td><input id="image_link_enabled" type="checkbox" <?php if ($link_vo->enabled == 1) { echo "checked";} ?>/></td></tr>
<tr><td>Open in new window</td><td><input type="checkbox" id="image_link_in_new_window" <?php if ($link_vo->in_new_window == 1) { echo "checked"; } ?>/><td></tr>
<tr><td>Type</td><td><select id="image_link_is_internal" name="is_internal">
<?php
if ($link_vo->is_internal == 1) {
	$int_str = "";
	$ext_str = "style=\"display: none;\"";
	$int_sel_str = "selected";
	$ext_sel_str = "";
} else {
	$int_str = "style=\"display: none;\"";
	$ext_str = "";
	$int_sel_str = "";
	$ext_sel_str = "selected";
}
?>
<option value="1" <?php echo $int_sel_str;?>>Internal</option><option value="0" <?php echo $ext_sel_str;?>>External</option>
</select></td></tr>
<tr id="image_link_external" <?php echo $ext_str;?>><td>Link</td><td><input style="width: 300px;" type="text" id="image_link_external_url" name="external_url" value="<?php echo $link_vo->external_url;?>"/></td></tr>
<tr id="image_link_internal" <?php echo $int_str;?>><td>Link</td><td><select id="image_link_internal_page_id" name="internal_page_id">
<?php
PagesAPI::GetPagesAsOptions($link_vo->internal_page_id);
?>
</select></td></tr>
<tr><td></td><td><button type="submit"><img src="pics/icons_24/check.png"/> Save changes</button></td></tr>
</table>
</form>
<?php } // End of if ($show_link) ?>

<div class="Heading">Choose from image archive</div>
<div id="ImageArchive" style="height: 450px; border: 1px solid transparent;">
</div><!--ImageArchive-->
<iframe id="image_upload_target" name="image_upload_target" style="width: 0px; height: 0px; border: 0px; display: none;" 
onload="HandleUploadResponse(this);"></iframe>
