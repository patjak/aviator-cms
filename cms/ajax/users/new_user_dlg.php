<?php
require_once("../include.php");
?>
<h2>Create new user</h2>
<form onsubmit="InsertNewUser($(this)); return false;" method="POST">
<table>
<tr><td>Full name</td><td><input type="text" name="fullname"/></td><td><i>(Optional)</i></tr>
<tr><td>Email</td><td><input type="text" name="email"/></td><td><i>(Optional)</i></td></tr>
<tr><td>Username</td><td><input type="text" name="username"/></td><td></td></tr>
<tr><td>Password</td><td><input type="password" name="password"/></td><td></td></tr>
<tr><td></td><td><button type="submit"><img src="<?php echo CMS_BASE;?>pics/icons_24/check.png"/> Create user</button>
</table>
</form>
