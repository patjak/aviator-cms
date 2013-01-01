<?php
require_once("../include.php");

$uid = (int)$_POST['uid'];

if ($uid == 0)
	exit();

$res = DB::Query("SELECT * FROM ".DB_PREFIX."users WHERE id=".$uid);
$user = DB::Obj($res, "DaoUser");

?>
<h2>Edit user</h2>
<form onsubmit="UpdateUser($(this)); return false;" method="POST">
<table>
<input type="hidden" name="uid" value="<?php echo $uid;?>"/>
<tr><td>Full name</td><td><input type="text" name="fullname" value="<?php echo $user->fullname;?>"/></td><td><i>(Optional)</i></tr>
<tr><td>Email</td><td><input type="text" name="email" value="<?php echo $user->email;?>"/></td><td><i>(Optional)</i></td></tr>
<tr><td>Username</td><td><input type="text" name="username" value="<?php echo $user->username;?>"/></td><td></td></tr>
<tr><td>Password</td><td><input type="password" name="password"/></td><td><i>(Leave empty to keep old password)</i></td></tr>
<tr><td></td><td><button type="submit"><img src="<?php echo CMS_BASE;?>pics/icons_24/check.png"/> Save changes</button>
</table>
</form>
