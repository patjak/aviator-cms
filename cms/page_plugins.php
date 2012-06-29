<div class="Box">
<h2><img src="pics/icons_32/plugin.png"/>Installed plugins</h2>
<table>
<tr><th>Name</th><th>Directory</th><th>Enabled</th></tr>
<?php
$dir = getcwd();
$dir .= "/../plugins";
$dir_res = opendir($dir);
while ($dir_name = readdir($dir_res)) {
	if (is_dir($dir."/".$dir_name) && $dir_name != "." && $dir_name != "..")
		echo "<tr><td><img src=\"pics/icons_32/edit.png\"/></td><td>".$dir_name."</td><td><input type=\"checkbox\" checked/></td></tr>";
}
closedir($dir_res);

?>
</table>
</div>
