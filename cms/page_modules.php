<?php
if (!isset($_GET['plugin']) || !isset($_GET['module']) || !isset($_GET['view'])) {
?>
<div class="Box" style="margin-bottom: 20px;">
<h2><img src="pics/icons_32/home.png"/> Modules</h2>
<table class="Dashboard"><tr>
<?php
foreach (DashboardCore::GetEntries() as $entry) {
	echo "<td>";

	switch ($entry->GetType()) {
	case DASHBOARD_TYPE_LINK:
		echo "<a href=\"".$entry->GetLink()."\" target=\"_blank\">".
		"<img src=\"".$entry->GetIcon64()."\"/><br/>".$entry->GetTitle()."</a>";
		break;

	case DASHBOARD_TYPE_MODULE:
		$plugin_id = $entry->GetPlugin()->GetId();
		$module_id = $entry->GetModule()->GetId();
		$type_internal_id = $entry->GetTypeInternalId();
		echo "<a href=\"?page=".PAGE_MODULES."&plugin=".$plugin_id."&module=".$module_id."&view=".$type_internal_id."\">".
		"<img src=\"".$entry->GetIcon64()."\"/><br/>".$entry->GetTitle()."</a>";
	}

	echo "</td>";
}
?>
</tr>
</table>
</div>
<?php
} else {
$plugin_id = (int)$_GET['plugin'];
$module_id = (int)$_GET['module'];
$view_id = (int)$_GET['view'];
$module = ModuleAPI::GetByPluginAndInternal($plugin_id, $module_id);
Context::SetDirectory($module->plugin->GetDirectory());
// $module->plugin->Install();
?>
<div class="Box" style="margin-bottom: 20px">
<h2><img src="<?php echo $module->GetIcon32();?>"/> <?php echo $module->GetTitle();?></h2>
<?php
require_once(SITE_PATH."plugins/".$module->plugin->GetDirectory()."/".$module->GetView($view_id));
?>
</div><!--Box-->
<?php
}
?>

