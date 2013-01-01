<?php
require_once("../include.php");

$res = DB::Query("SELECT * FROM ".DB_PREFIX."user_groups ORDER BY name ASC");

if (DB::NumRows($res) > 0) {
?>
<table id="UserGroupsTable" style="margin-bottom: 40px; width: 100%;" class="List Minimizable">
<caption>Groups</caption>
<tr><th>Name</th><th style="text-align: center;">Members</th><th></th></tr>
<?php
	while ($group_vo = DB::Obj($res, "DaoUserGroup")) {
		$res_count = DB::Query("SELECT id FROM ".DB_PREFIX."user_group_members WHERE group_id=".$group_vo->id);
		$num_members = DB::NumRows($res_count);
		echo "<tr><td>".$group_vo->name."</td><td style=\"text-align: center;\">".$num_members."</td>".
		"<td style=\"text-align: right;\">".
		"<span class=\"Button\" onclick=\"ShowEditGroupDlg(".$group_vo->id.");\">".
		"<img src=\"pics/icons_24/edit.png\" alt=\"Edit group\" title=\"Edit group\"/></span> ".
		"<span class=\"Button\" onclick=\"AskDeleteGroup(".$group_vo->id.");\">".
		"<img src=\"pics/icons_24/trash.png\" alt=\"Delete group\" title=\"Delete group\"/></span>".
		"</td></tr>";

	}
?>
</table>
<?php
} else {
	echo "<span class=\"Button\" onclick=\"ShowNewGroupDlg();\"><img src=\"pics/icons_32/paper_new.png\"/> Create new group</span>";
}
?>

<table id="UsersTable" style="width: 100%;" class="List Minimizable">
<caption>Users</caption>
<tr><th>Username</th><th>Name</th><th>Groups</th><th></th></tr>
<?php
$res = DB::Query("SELECT * FROM ".DB_PREFIX."users ORDER BY username ASC");
while ($user_vo = DB::Obj($res, "DaoUser")) {
	if ($user_vo->full_access == 1)
		$full_access = "Yes";
	else
		$full_access = "No";

	echo "<tr><td>".$user_vo->username."</td><td><nobr>".$user_vo->fullname."</nobr></td><td></td>".
	"<td style=\"text-align: right;\">".
	"<span class=\"Button\" onclick=\"ShowEditUserDlg(".$user_vo->id.");\">".
	"<img src=\"pics/icons_24/edit.png\" alt=\"Edit user\" title=\"Edit user\"/></span> ".
	"<span class=\"Button\" onclick=\"AskDeleteUser(".$user_vo->id.");\">".
	"<img src=\"pics/icons_24/trash.png\" alt=\"Delete user\" title=\"Delete user\"/></span>".
	"</td></tr>";
}
?>
</table>

