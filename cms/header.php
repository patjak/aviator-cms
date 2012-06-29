<?php require_once("secure.php"); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="style/style.css"/>
<script type="text/javascript" src="jquery/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="jquery/js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/lib.js"></script>
<script type="text/javascript" src="js/loading.js"></script>
<script type="text/javascript" src="js/dialogs.js"></script>
<script type="text/javascript" src="js/contents.js"></script>
<script type="text/javascript" src="js/contents_edit.js"></script>
<script type="text/javascript" src="js/dashboard.js"></script>
<script type="text/javascript" src="js/image_uploader.js"></script>
<?php
foreach (ModuleCore::GetEntries() as $entry) {
	$module_id = $entry->GetId();
	$plugin_id = $entry->plugin->GetId();

	if (!isset($_GET['plugin']) || !isset($_GET['module']))
		break;

	if ($plugin_id != (int)$_GET['plugin'] || $module_id != (int)$_GET['module'])
		continue;

	// FIXME: Add module js and css
}

if (URL::GetPage() == PAGE_CONTENTS_EDIT) {
	foreach (ContentCore::GetCssBackendList() as $css) {
		echo "<link rel=\"stylesheet\" href=\"".$css."\"/>\n";
	}

	foreach (ContentCore::GetJsBackendList() as $js) {
		echo "<script type=\"text/javascript\" src=\"".$js."\"></script>\n";
	}
}
?>
<title>Noorsbron CMS 5.0</title>
</head>
<body>
<div id="Header">
<img src="pics/logo.png" style="float: left; margin: 20px;"/>
<div id="TopMenu">
<?php
$page = URL::GetPage();

// Mark the selected top menu item
$sel_home = "";
$sel_contents = "";
$sel_modules = "";
$sel_themes = "";
$sel_plugins = "";
$sel_site = "";
$sel_profile = "";
$sel_setup = "";

switch ($page) {
case PAGE_HOME:
	$sel_home = "Selected";
	break;
case PAGE_CONTENTS:
	$sel_contents = "Selected";
	break;
case PAGE_MODULES:
	$sel_modules = "Selected";
	break;
case PAGE_THEMES:
	$sel_themes = "Selected";
	break;
case PAGE_PLUGINS:
	$sel_plugins = "Selected";
	break;
case PAGE_SETUP:
	$sel_setup = "Selected";
	break;
case PAGE_PROFILE:
	$sel_profile = "Selected";
	break;

}
?>
<table><tr>
<td>
<a class="<?php echo $sel_home;?>" href="?page=<?php echo PAGE_HOME;?>"><img src="pics/icons_64/home.png"/><br/>Home</a>
</td><td>
<a class="<?php echo $sel_contents;?>" href="?page=<?php echo PAGE_CONTENTS;?>"><img src="pics/icons_64/box.png"/><br/>Contents</a>
</td><td>
<a class="<?php echo $sel_modules;?>" href="?page=<?php echo PAGE_MODULES;?>"><img src="pics/icons_64/module.png"/><br/>Modules</a>
</td><!--<td>
<a class="<?php echo $sel_themes;?>" href="?page=<?php echo PAGE_THEMES;?>"><img src="pics/icons_64/television.png"/><br/>Themes</a>
</td>--><!--<td>
<a class="<?php echo $sel_plugins;?>" href="?page=<?php echo PAGE_PLUGINS;?>"><img src="pics/icons_64/plugin.png"/><br/>Plugins</a>
</td>--><td>
<?php if (!HIDE_SITE_SETTINGS) { ?>

<a class="<?php echo $sel_setup;?>" href="?page=<?php echo PAGE_SETUP;?>"><img src="pics/icons_64/settings.png"/><br/>Site setup</a>
</td>

<?php } ?>
<!--<td><a class="<?php echo $sel_profile;?>" href="?page=<?php echo PAGE_PROFILE;?>"><img src="pics/icons_64/profile.png"/><br/>Your profile</a>
</td>--><td>
<a href="logout.php"><img src="pics/icons_64/power_off.png"/><br/>Log out</a>
</td></tr></table>
<div style="clear: both;"></div>
</div><!--TopMenu-->
</div><!--Header-->
<div id="Background">
<div id="Page">
