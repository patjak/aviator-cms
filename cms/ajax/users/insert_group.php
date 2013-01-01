<?php
require_once("../include.php");

$name = $_POST['name'];
$description = $_POST['description'];

$err = false;

if ($name == "") {
	echo "<p>You must enter a name for your group</p>";
	$err = true;
}

$name_safe = mysql_real_escape_string($name);
if ($name != $name_safe) {
	echo "<p>Invalid group name</p>";
	$err = true;
}

$res = DB::Query("SELECT id FROM ".DB_PREFIX."user_groups WHERE name='".$name_safe."'");
if (DB::NumRows($res) > 0) {
	echo "<p>A group with that name already exists</p>";
	$err = true;
}

if ($err) {
	Ajax::SetStatus(AJAX_STATUS_NOTICE);
	exit();
}

$group = new DaoUserGroup();
$group->name = $name;
$group->description = $description;

DB::Insert(DB_PREFIX."user_groups", $group);

$gid = DB::InsertID();

echo "ShowEditGroupDlg(".$gid.")";
?>
