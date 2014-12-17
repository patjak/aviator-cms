<?php
$username = Settings::Get("admin_username");
?>
<div class="Box">
<h2><img src="pics/icons_32/profile.png"/>Profile settings</h2>
<div class="Heading">Personal information</div>
<table>
<tr><td style="width: 180px;">Username</td><td><input type="text" value="<?php echo $username;?>"/></td></tr>
<tr><td>Firstname</td><td><input type="text"/></td></tr>
<tr><td>Lastname</td><td><input type="text"/></td></tr>
<tr><td>Email</td><td><input type="text"/></td></tr>
<tr><td></td><td> <button><img src="pics/icons_24/check.png"/> Save changes</button></td></tr>
</table>
<div class="Heading">Change password</div>
<table>
<tr><td style="width: 180px;">Current password</td><td><input type="password"/></td></tr>
<tr><td>New password</td><td><input type="password"/></td></tr>
<tr><td>Repeat password</td><td><input type="password"/></td></tr>
<tr><td></td><td> <button><img src="pics/icons_24/check.png"/> Change password</button></td></tr>
</table>
</div>
