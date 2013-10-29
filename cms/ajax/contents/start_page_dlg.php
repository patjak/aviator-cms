<?php

require_once("../include.php");

function EchoSiteTree($parent_id, $selected, $depth_str = "-")
{
	if ($parent_id == 0) {
		$parent_str = "IS NULL";
		echo "<option value=\"0\">(First page in tree)</option>";
	} else {
		$parent_str = "=".$parent_id;
	}

	$res = DB::Query("SELECT * FROM ".DB_PREFIX."pages WHERE parent_id ".$parent_str." ORDER BY sort");

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

if (isset($_POST['pid']))
	$pid = (int)$_POST['pid'];
else
	$pid = 0;

?>
<form onsubmit="UpdateStartPage($(this)); return false;">
<h2>Select start page</h2>
<p>
<select name="start_page">
<?php EchoSiteTree(0, Settings::Get("site_start_page")); ?>
</select>
</p>
<div class="Heading"></div>
<div style="text-align: center;"><button type="submit"><img src="pics/icons_24/check.png"/> Save changes</button></div>
</form>
