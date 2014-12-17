<?php
$site_title = Settings::Get("site_title");
$max_page_depth = Settings::Get("max_page_depth");
$max_top_level_pages = Settings::Get("max_top_level_pages");
?>
<div class="Box">
<h2><img src="pics/icons_32/settings.png"/>Site setup</h2>
<div class="Heading">General site settings</div>
<table>
<tr><td style="width: 250px;">Site title</td><td><input type="text" value="<?php echo $site_title;?>"/></td></tr>
<tr><td>Max page depth</td><td><input type="text" style="width: 30px; text-align: center;" value="<?php echo $max_page_depth;?>"/></td>
<td>(0 = unlimited)</td></tr>
<tr><td>Max top level pages</td><td><input type="text" style="width: 30px; text-align: center;" value="<?php echo $max_top_level_pages;?>"/></td>
<td>(0 = unlimited)</td></tr>
<tr><td>Show contents menu</td><td><input type="checkbox" checked/></td><td></td></tr>
<tr><td>Show modules menu</td><td><input type="checkbox" checked/></td><td>(Modules can still be reached through the dashboard)</td></tr>
<tr><td>Show themes menu</td><td><input type="checkbox" checked/></td><td></td></tr>
<tr><td>Show plugins menu</td><td><input type="checkbox" checked/></td><td></td></tr>
<tr><td>Lock site tree</td><td><input type="checkbox"/></td><td></td></tr>
<tr><td>Allow change of start page</td><td><input type="checkbox"/></td><td></td></tr>
</table>

<div class="Heading">Additional page settings</div>
<table>
<tr><td style="width: 250px;">Allow change of type</td><td><input type="checkbox"/></td></tr>
<tr><td>Allow change of style</td><td><input type="checkbox"/></td></tr>
<tr><td>Allow change of rules</td><td><input type="checkbox"/></td></tr>
</table>

<div class="Heading"></div>
<div style="text-align: center;">
<button><img src="pics/icons_24/check.png"/> Save changes</button>
</div>
</div><!--Box-->
