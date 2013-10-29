<?php

require_once("../include.php");

$link_id = (int)$_POST['link_id'];

$res = DB::Query("SELECT * FROM ".DB_PREFIX."links WHERE id=".$link_id);
$link_vo = DB::RowToObj("DaoLink", $res[0]); 

$link_vo->name = $_POST['name'];
$link_vo->is_internal = (int)$_POST['is_internal'];
$link_vo->internal_page_id = (int)$_POST['internal_page_id'];
$link_vo->external_url = $_POST['external_url'];
$link_vo->in_new_window = (int)$_POST['in_new_window'];
$link_vo->enabled = (int)$_POST['enabled'];

?>
<h2>Edit link</h2>
<form method="POST" action="ajax/link_picker/update_link.php" onsubmit="UpdateLinkPicker(<?php echo $link_vo->id;?>); return false;">
<table>

<tr><td style="width: 200px;">Enable link</td>
<td><input id="link_picker_enabled" type="checkbox" <?php if ($link_vo->enabled == 1) { echo "checked";} ?>/></td></tr>

<tr><td>Open in new window</td>
<td><input type="checkbox" id="link_picker_in_new_window" <?php if ($link_vo->in_new_window == 1) { echo "checked"; } ?>/><td></tr>

<tr><td>Name</td>
<td><input type="text" id="link_picker_name" value="<?php echo htmlentities($link_vo->name, ENT_QUOTES, "UTF-8");?>"/></td></tr>

<tr><td>Type</td>
<td><select id="link_picker_is_internal" onchange="UpdateLinkPickerType($(this));">
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

<tr id="link_picker_external" <?php echo $ext_str;?>><td>Link</td>
<td><input style="width: 300px;" type="text" id="link_picker_external_url" value="<?php echo $link_vo->external_url;?>"/></td></tr>

<tr id="link_picker_internal" <?php echo $int_str;?>><td>Link</tda
><td><select id="link_picker_internal_page_id">
<?php
PagesAPI::GetPagesAsOptions($link_vo->internal_page_id);
?>
</select></td></tr>
<tr><td></td><td><button type="submit"><img src="pics/icons_24/check.png"/> Apply changes</button></td></tr>
</table>
</form>
