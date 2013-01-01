<?php
require_once("../include.php");
?>
<h2>Edit group permissions</h2>

<form onsubmit="UpdateGroup($(this)); return false;" method="POST">
<table class="List Minimizable" style="width: 100%; margin-bottom: 0px;">
<caption>Page access</caption>
<tr><th>Page name</th><th>Location</th><th>Include subpages</th><th></th></tr>
<tr><td>Slowmo</td><td>Startsida->Media->Videos</td><td>Yes</td><td><span class="Button"><img src="<?php echo CMS_BASE;?>pics/icons_24/trash.png"</span></td></tr>
<tr><td>Slowmo</td><td>Startsida->Media->Videos</td><td>Yes</td><td><span class="Button"><img src="<?php echo CMS_BASE;?>pics/icons_24/trash.png"</span></td></tr>
<tr><td>Slowmo</td><td>Startsida->Media->Videos</td><td>Yes</td><td><span class="Button"><img src="<?php echo CMS_BASE;?>pics/icons_24/trash.png"</span></td></tr>
</table>
<table>
<tr><th colspan="4">Add page permission</th></tr>
<tr><td colspan="4"><select><option>Startsida</option></select> <input type="checkbox" name="include_subpages"/>Include subpages <button><img src="pics/icons_24/check.png"/> Add permission</button></td></tr>
</table>

<table class="List Minimizable" style="width: 100%;">
<caption>Plugin permissions</caption>
</table>
</form>
