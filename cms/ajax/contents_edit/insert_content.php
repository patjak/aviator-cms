<?php
require_once("../include.php");

$page_id = (int)$_GET['page_id'];
$section_id = (int)$_GET['section_id'];
$plugin_id = (int)$_GET['plugin_id'];
$internal_id = (int)$_GET['internal_id'];

$content_vo = new DaoContent();

$content_vo->plugin_id = $plugin_id;
$content_vo->page_id = $page_id;
$content_vo->section_id = $section_id;
$content_vo->internal_id = $internal_id;

DB::Insert($content_vo);
$content_vo->sort = $content_vo->id;
DB::Update($content_vo);

$content = ContentCore::GetByPluginAndInternal($plugin_id, $internal_id);
$content->id = $content_vo->id;
$content->Create();

// Return the new element (FIXME: Shouldn't duplicate page_contents.php here)
?>
<form id="content_form_<?php echo $content->id;?>" action="" method="POST" onsubmit="return false;" enctype="multipart/form-data">
<div class="Box Content" style="width: 790px;">
<h2><span class="ContentButtons" style="float: right;">
<span class="Button" onclick="MoveContentTop(<?php echo $content->id;?>)">
<img alt="Move to top" title="Move to top" src="pics/icons_24/arrow_top.png"/></span>
<span class="Button" onclick="MoveContentUp(<?php echo $content->id;?>)">
<img alt="Move up" title="Move up" src="pics/icons_24/arrow_up.png"/></span>
<span class="Button" onclick="MoveContentDown(<?php echo $content->id;?>)">
<img alt="Move down" title="Move down" src="pics/icons_24/arrow_down.png"/></span>
<span class="Button" onclick="MoveContentBottom(<?php echo $content->id;?>)">
<img alt="Move to bottom" title="Move to bottom" src="pics/icons_24/arrow_bottom.png"/></span>
<span class="Button" onclick="AskDeleteContent(<?php echo $content_vo->plugin_id.", ".
						$content->GetId().", ".
						$content->id;?>)">
<img alt="Delete" title="Delete" src="pics/icons_24/trash.png"/></span>

</span><img src="<?php echo $content->GetIcon32();?>" alt="<?php echo $content->GetTitle();?>" title="<?php echo $content->GetTitle();?>"/> 
<?php
if ($content_vo->name == "")
	$content_name = $content->GetTitle();
else
	$content_name = $content_vo->name;
?>
<span><?php echo $content_name;?></span>
<input style="width: 300px; margin: 0px; display: none;" type="text" name="content_name" value="<?php echo $content_name;?>"/></h2>
<div class="ContentEdit"></div><!--ContentEdit-->
<div class="Button" style="text-align: center;" 
onclick="ShowContent($(this).parent(), <?php echo $content_vo->plugin_id.", ".$content->GetId().", ".$content_vo->id;?>); $(this).hide();">
<img src="pics/slide_down.png"/></div>

</div></form>
