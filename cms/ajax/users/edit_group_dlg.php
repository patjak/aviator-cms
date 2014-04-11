<?php
require_once("../include.php");
$group_id = (int)$_POST['gid'];
?>
<h2>Edit group permissions</h2>

<form onsubmit="UpdateGroup($(this)); return false;" method="POST">
<table class="List Minimizable" style="width: 100%; margin-bottom: 0px;">
<caption>Page access</caption>
<tr><th>Page name</th><th>Location</th><th>Subpages</th><th></th></tr>
<tr><td>Slowmo</td><td>Startsida->Media->Videos</td><td>Yes</td><td><span class="Button"><img src="<?php echo CMS_BASE;?>pics/icons_24/trash.png"</span></td></tr>
<tr><td>Slowmo</td><td>Startsida->Media->Videos</td><td>Yes</td><td><span class="Button"><img src="<?php echo CMS_BASE;?>pics/icons_24/trash.png"</span></td></tr>
<tr><td>Slowmo</td><td>Startsida->Media->Videos</td><td>Yes</td><td><span class="Button"><img src="<?php echo CMS_BASE;?>pics/icons_24/trash.png"</span></td></tr>
<?php
function BuildLocation($page_vo, $list = false) {
	if ($list === false)
		$list = array();

	// Add to beginning of list
	array_unshift($list, $page_vo->title);

	if ($page_vo->parent_id > 0) {
		$parent_vo = DB::ObjById("DaoPage", DB_PREFIX."pages", $page_vo->parent_id);
		BuildLocation($parent_vo, $list);
	} else {
		return $list;
	}
}

$res = DB::Query("SELECT * FROM ".DB_PREFIX."resources WHERE group_id=".$group_id." AND page_id != NULL ORDER BY id");
foreach ($res as $row) {
	$resource_vo = DB::RowToObj("DaoResource", $row);
	$page_vo = DB::ObjById("DaoPage", $resource_vo->page_id);
	if ($resource_vo->subpages == 1)
		$subpages_str = "Yes";
	else
		$subpages_str = "No";

	$list = BuildLocation($page_vo);
	$location = "";
	for ($i = 0; $i < count($list); $i++) {
		$location .= $title;
		if ($i < (count($list) - 1))
			$location .= " > ";
	}

	echo "<tr><td>".$location."</td><td>".$subpage_str."</td><td><span class=\"Button\"><img src=\"".CMS_BASE."pics/icons_24/trash.png\"/></span></td></tr>";
}
?>
</table>
<table>
<tr><th colspan="4">Add page permission</th></tr>
<tr><td colspan="4"><form onsubmit="InsertGroupPagePermission($(this), <?php echo $group_id;?>); return false;)">
<input type="hidden" name="gid" value="<?php echo $group_id;?>"/>
<select name="page_id">
<option value="0">- All pages -</option>
<?php
PagesAPI::GetPagesAsOptions();
?>
</select> <input type="checkbox" name="subpages"/>Include subpages <button type="submit"><img src="pics/icons_24/check.png"/> Add permission</button></td></tr>
</table>

<table class="List Minimizable" style="width: 100%;">
<caption>Plugin permissions</caption>
</table>
</form>
