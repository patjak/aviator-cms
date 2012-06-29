<?php

require_once("../include.php");
$image_ref_id = (int)$_GET['image_ref_id'];
$image_id = (int)$_GET['image_id'];
$max_width = (int)$_GET['max_width'];
$max_height = (int)$_GET['max_height'];
$min_width = (int)$_GET['min_width'];
$min_height = (int)$_GET['min_height'];

$res = DB::Query("SELECT image_id FROM ".DB_PREFIX."image_refs WHERE id=".$image_ref_id);
$row = DB::Row($res);

$image = new Image($image_id);
$image->SetMaxWidth($max_width);
$image->SetMaxHeight($max_height);
$image->SetMinWidth($min_width);
$image->SetMinHeight($min_height);

echo $image->GetImgTag();

?>
