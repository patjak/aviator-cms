<?php

require_once("../include.php");

$plugin_id = (int)$_GET['plugin_id'];
$internal_id = (int)$_GET['internal_id'];
$content_id = (int)$_GET['content_id'];

$content = ContentCore::GetByPluginAndInternal($plugin_id, $internal_id);
$content->id = $content_id;
Context::SetDirectory($content->plugin->GetDirectory());
$content->Edit();
?>

<div class="Heading"></div>
<div style="text-align: center;">
<button onclick="SaveContent($(this).parent().parent().parent(),
			     <?php echo $plugin_id;?>,
			     <?php echo $internal_id;?>,
			     <?php echo $content_id;?>); return false;" style="margin-right: 20px;">
<img src="pics/icons_24/check.png"/> Save</button>
<button style="margin-left: 20px;" onclick="HideContent($(this).parent().parent().parent());">
<img src="pics/icons_24/cross.png"/> Cancel</button>
</div>

