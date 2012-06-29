<div class="Box" style="margin-bottom: 20px;">
<h2><img src="pics/icons_32/home.png"/> Dashboard</h2>
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
