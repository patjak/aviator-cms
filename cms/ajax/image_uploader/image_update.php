<?php

require_once("../include.php");
$image_ref_id = (int)$_GET['image_ref_id'];
$image_id = (int)$_GET['image_id'];
$max_width = (int)$_GET['max_width'];
$max_height = (int)$_GET['max_height'];
$min_width = (int)$_GET['min_width'];
$min_height = (int)$_GET['min_height'];

$image = new Image($image_id);
$image->SetMaxWidth($max_width);
$image->SetMaxHeight($max_height);
$image->SetMinWidth($min_width);
$image->SetMinHeight($min_height);

if ($image_id > 0)
	echo $image->GetImgTag();
else
	echo "<img src=\"".CMS_BASE."pics/icons_64/image_white.png\" style=\"margin: 20px;\"/>";

?>
