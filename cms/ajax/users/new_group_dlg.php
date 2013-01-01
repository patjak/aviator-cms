<?php
require_once("../include.php");
?>
<h2>Create new group</h2>

<form onsubmit="InsertNewGroup($(this)); return false;" method="POST">
<table>
<tr><td>Name</td><td><input type="text" name="name"/></td></tr>
<tr><td>Description</td><td><input type="text" name="description"/></td></tr>
<tr><td></td><td><button type="submit"><img src="<?php echo CMS_BASE;?>pics/icons_24/check.png"/> Create group</button></td></tr>
</table>
</form>
