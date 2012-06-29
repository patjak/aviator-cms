<?php
require_once("../include.php");

$image_ref_id = (int)$_GET['image_ref_id'];
$image_id = (int)$_GET['image_id'];

echo "<h2>Set cropping hint</h2>";

$image = new Image($image_id);
$image->SetMaxWidth(570);
$image->SetMaxHeight(400);
$img_tag = $image->GetImgTag();
$width = $image->GetWidth();
$height = $image->GetHeight();
$crop_left = $width / 2;
$crop_top = $height / 2;
?>
<div style="text-align: center;">
<div class="CroppingHint" style="width: <?php echo $width;?>px; height: <?php echo $height;?>px; margin-left: auto; margin-right: auto; text-align: center; position: relative;">
<input type="hidden" id="CroppingImageRefId" value="<?php echo $image_ref_id;?>"/>
<?php echo $img_tag;?>
<div class="y" style="left: <?php echo $crop_left;?>px;"></div>
<div class="x" style="top: <?php echo $crop_top;?>px;"></div>
</div></div>
<div class="Heading"></div>
<div style="text-align: center;"><Button type="button" onclick="DialogHide();"><img src="pics/icons_24/check.png"/> Done</button></div>
